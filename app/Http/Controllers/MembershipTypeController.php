<?php

namespace App\Http\Controllers;

use App\Models\MembershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class MembershipTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        try {
            // التأكد من وجود الجدول أولاً
            if (!\Schema::hasTable('membership_types')) {
                Log::warning('membership_types table does not exist');
                return view('membership-types.index', ['membershipTypes' => collect([])])
                    ->with('error', 'جدول أنواع العضويات غير موجود. يرجى تشغيل المايجريشن أولاً.');
            }

            // جلب البيانات مع معالجة الأخطاء
            $membershipTypes = MembershipType::orderBy('sort_order')->orderBy('name')->get();
            
            // التأكد من أن النتيجة هي Collection
            if (!$membershipTypes instanceof \Illuminate\Database\Eloquent\Collection) {
                Log::error('MembershipTypes query did not return a Collection', [
                    'type' => gettype($membershipTypes),
                    'value' => $membershipTypes
                ]);
                $membershipTypes = collect([]);
            }
            
            Log::info('MembershipTypes loaded successfully', [
                'count' => $membershipTypes->count(),
                'type' => get_class($membershipTypes)
            ]);
            
            return view('membership-types.index', compact('membershipTypes'));
            
        } catch (QueryException $e) {
            Log::error('Database error in MembershipTypeController@index', [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            return view('membership-types.index', ['membershipTypes' => collect([])])
                ->with('error', 'خطأ في قاعدة البيانات: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            Log::error('General error in MembershipTypeController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('membership-types.index', ['membershipTypes' => collect([])])
                ->with('error', 'حدث خطأ أثناء تحميل أنواع العضويات: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('membership-types.create');
        } catch (\Exception $e) {
            Log::error('Error in MembershipTypeController@create', ['error' => $e->getMessage()]);
            return redirect()->route('membership-types.index')->with('error', 'حدث خطأ أثناء تحميل صفحة الإنشاء.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'features' => 'nullable|array',
                'features.*' => 'string',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            // معالجة المدة المخصصة
            if ($request->has('custom_duration_days') && $request->custom_duration_days) {
                $validated['duration_days'] = $request->custom_duration_days;
            }

            // تنظيف المميزات
            if (isset($validated['features'])) {
                $validated['features'] = array_filter($validated['features'], function($feature) {
                    return !empty(trim($feature));
                });
            }

            // إنشاء slug
            $validated['slug'] = Str::slug($validated['name']);
            
            // التأكد من أن الـ slug فريد
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (MembershipType::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            MembershipType::create($validated);

            return redirect()->route('membership-types.index')->with('success', 'تم إنشاء نوع العضوية بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error creating membership type', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء نوع العضوية: ' . $e->getMessage());
        }
    }

    public function show(MembershipType $membershipType)
    {
        try {
            $membershipType->load(['userMemberships.user', 'activeUserMemberships']);
            return view('membership-types.show', compact('membershipType'));
        } catch (\Exception $e) {
            Log::error('Error showing membership type', ['error' => $e->getMessage()]);
            return redirect()->route('membership-types.index')->with('error', 'حدث خطأ أثناء عرض تفاصيل العضوية.');
        }
    }

    public function edit(MembershipType $membershipType)
    {
        try {
            if ($membershipType->is_protected) {
                return redirect()->route('membership-types.index')->with('error', 'لا يمكن تعديل هذا النوع من العضوية لأنه محمي من النظام.');
            }

            return view('membership-types.edit', compact('membershipType'));
        } catch (\Exception $e) {
            Log::error('Error in edit method', ['error' => $e->getMessage()]);
            return redirect()->route('membership-types.index')->with('error', 'حدث خطأ أثناء تحميل صفحة التعديل.');
        }
    }

    public function update(Request $request, MembershipType $membershipType)
    {
        try {
            if ($membershipType->is_protected) {
                return redirect()->route('membership-types.index')->with('error', 'لا يمكن تعديل هذا النوع من العضوية لأنه محمي من النظام.');
            }

            $validated = $request->validate([
                'name' => 'required|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'features' => 'nullable|array',
                'features.*' => 'string',
                'sort_order' => 'nullable|integer|min:0'
            ]);

            // معالجة المدة المخصصة
            if ($request->has('custom_duration_days') && $request->custom_duration_days) {
                $validated['duration_days'] = $request->custom_duration_days;
            }

            // تنظيف المميزات
            if (isset($validated['features'])) {
                $validated['features'] = array_filter($validated['features'], function($feature) {
                    return !empty(trim($feature));
                });
            }

            // تحديث slug إذا تغير الاسم
            if ($validated['name'] !== $membershipType->name) {
                $newSlug = Str::slug($validated['name']);
                $originalSlug = $newSlug;
                $counter = 1;
                while (MembershipType::where('slug', $newSlug)->where('id', '!=', $membershipType->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $validated['slug'] = $newSlug;
            }

            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            $membershipType->update($validated);

            return redirect()->route('membership-types.index')->with('success', 'تم تحديث نوع العضوية بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating membership type', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث نوع العضوية: ' . $e->getMessage());
        }
    }

    public function destroy(MembershipType $membershipType)
    {
        try {
            if ($membershipType->is_protected) {
                return redirect()->route('membership-types.index')->with('error', 'لا يمكن حذف هذا النوع من العضوية لأنه محمي من النظام.');
            }

            if (!$membershipType->canBeDeleted()) {
                return redirect()->route('membership-types.index')->with('error', 'لا يمكن حذف هذا النوع من العضوية لوجود مشتركين به.');
            }

            $membershipType->delete();

            return redirect()->route('membership-types.index')->with('success', 'تم حذف نوع العضوية بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting membership type', ['error' => $e->getMessage()]);
            return redirect()->route('membership-types.index')->with('error', 'حدث خطأ أثناء حذف نوع العضوية: ' . $e->getMessage());
        }
    }

    public function toggleStatus(MembershipType $membershipType)
    {
        try {
            if ($membershipType->is_protected) {
                return redirect()->route('membership-types.index')->with('error', 'لا يمكن تعديل حالة هذا النوع من العضوية لأنه محمي من النظام.');
            }

            $membershipType->is_active = !$membershipType->is_active;
            $membershipType->save();

            $status = $membershipType->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
            return redirect()->route('membership-types.index')->with('success', $status . ' نوع العضوية بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error toggling membership type status', ['error' => $e->getMessage()]);
            return redirect()->route('membership-types.index')->with('error', 'حدث خطأ أثناء تغيير حالة العضوية: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkoutController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|coach|client']);
    }

    /**
     * عرض قائمة التمارين
     */
    public function index(Request $request)
    {
        try {
            $query = Workout::with('user');

            // تطبيق الصلاحيات
            if (!auth()->user()->hasRole('admin')) {
                if (auth()->user()->hasRole('coach')) {
                    // المدرب يرى تماrinه فقط
                    $query->where('user_id', auth()->id());
                } elseif (auth()->user()->hasRole('client')) {
                    // العميل يرى التمارين النشطة فقط
                    $query->where('status', true);
                }
            }

            // فلترة حسب الصعوبة
            if ($request->filled('difficulty')) {
                $query->where('difficulty', $request->difficulty);
            }

            // فلترة حسب الحالة
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // البحث في الاسم والوصف
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            $workouts = $query->latest()->paginate(12);

            return view('workouts.index', compact('workouts'));
        } catch (\Exception $e) {
            Log::error('Error in WorkoutController@index', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء تحميل التمارين: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء تمرين جديد
     */
    public function create()
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->hasAnyRole(['admin', 'coach'])) {
            abort(403, 'ليس لديك صلاحية لإنشاء التمارين.');
        }

        return view('workouts.create');
    }

    /**
     * حفظ تمرين جديد
     */
    public function store(Request $request)
    {
        try {
            // التحقق من الصلاحيات
            if (!auth()->user()->hasAnyRole(['admin', 'coach'])) {
                abort(403, 'ليس لديك صلاحية لإنشاء التمارين.');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'duration' => 'required|integer|min:1|max:300',
                'difficulty' => 'required|in:easy,medium,hard',
                'video_url' => 'nullable|url|max:500',
            ]);

            $validated['user_id'] = auth()->id();
            $validated['status'] = $request->has('status');

            Workout::create($validated);

            return redirect()->route('workouts.index')->with('success', 'تم إنشاء التمرين بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error creating workout', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء التمرين: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل التمرين
     */
    public function show(Workout $workout)
    {
        try {
            // التحقق من الصلاحيات
            if (!auth()->user()->hasRole('admin') && 
                !auth()->user()->hasRole('coach') && 
                !$workout->status) {
                abort(403, 'هذا التمرين غير متاح.');
            }

            $workout->load(['user', 'schedules']);
            
            return view('workouts.show', compact('workout'));
        } catch (\Exception $e) {
            Log::error('Error showing workout', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء عرض التمرين.');
        }
    }

    /**
     * عرض نموذج تعديل التمرين
     */
    public function edit(Workout $workout)
    {
        // التحقق من الصلاحيات
        if (!$workout->canEdit(auth()->user())) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا التمرين.');
        }

        return view('workouts.edit', compact('workout'));
    }

    /**
     * تحديث التمرين
     */
    public function update(Request $request, Workout $workout)
    {
        try {
            // التحقق من الصلاحيات
            if (!$workout->canEdit(auth()->user())) {
                abort(403, 'ليس لديك صلاحية لتعديل هذا التمرين.');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'duration' => 'required|integer|min:1|max:300',
                'difficulty' => 'required|in:easy,medium,hard',
                'video_url' => 'nullable|url|max:500',
            ]);

            $validated['status'] = $request->has('status');

            $workout->update($validated);

            return redirect()->route('workouts.index')->with('success', 'تم تحديث التمرين بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating workout', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث التمرين: ' . $e->getMessage());
        }
    }

    /**
     * حذف التمرين
     */
    public function destroy(Workout $workout)
    {
        try {
            // التحقق من الصلاحيات
            if (!$workout->canDelete(auth()->user())) {
                abort(403, 'ليس لديك صلاحية لحذف هذا التمرين.');
            }

            $workout->delete();

            return redirect()->route('workouts.index')->with('success', 'تم حذف التمرين بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting workout', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء حذف التمرين: ' . $e->getMessage());
        }
    }
}
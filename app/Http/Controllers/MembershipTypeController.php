<?php

namespace App\Http\Controllers;

use App\Models\MembershipType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MembershipTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $membershipTypes = MembershipType::ordered()->get();
        return view('membership-types.index', compact('membershipTypes'));
    }

    public function create()
    {
        return view('membership-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'sort_order' => 'nullable|integer|min:0'
        ]);

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
    }

    public function show(MembershipType $membershipType)
    {
        $membershipType->load(['userMemberships.user', 'activeUserMemberships']);
        return view('membership-types.show', compact('membershipType'));
    }

    public function edit(MembershipType $membershipType)
    {
        if ($membershipType->is_protected) {
            return redirect()->route('membership-types.index')->with('error', 'لا يمكن تعديل هذا النوع من العضوية لأنه محمي من النظام.');
        }

        return view('membership-types.edit', compact('membershipType'));
    }

    public function update(Request $request, MembershipType $membershipType)
    {
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
    }

    public function destroy(MembershipType $membershipType)
    {
        if ($membershipType->is_protected) {
            return redirect()->route('membership-types.index')->with('error', 'لا يمكن حذف هذا النوع من العضوية لأنه محمي من النظام.');
        }

        if (!$membershipType->canBeDeleted()) {
            return redirect()->route('membership-types.index')->with('error', 'لا يمكن حذف هذا النوع من العضوية لوجود مشتركين به.');
        }

        $membershipType->delete();

        return redirect()->route('membership-types.index')->with('success', 'تم حذف نوع العضوية بنجاح.');
    }

    public function toggleStatus(MembershipType $membershipType)
    {
        if ($membershipType->is_protected) {
            return redirect()->route('membership-types.index')->with('error', 'لا يمكن تعديل حالة هذا النوع من العضوية لأنه محمي من النظام.');
        }

        $membershipType->is_active = !$membershipType->is_active;
        $membershipType->save();

        $status = $membershipType->is_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        return redirect()->route('membership-types.index')->with('success', $status . ' نوع العضوية بنجاح.');
    }
}
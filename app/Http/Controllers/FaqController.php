<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin'])->except(['index', 'show']);
    }

    /**
     * Display a listing of FAQs
     */
    public function index()
    {
        $faqs = Faq::with('user')
            ->ordered()
            ->get()
            ->groupBy('category');
            
        return view('faqs.index', compact('faqs'));
    }

    /**
     * Display the admin listing of FAQs
     */
    public function adminIndex()
    {
        $faqs = Faq::with('user')->ordered()->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created FAQ
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        try {
            // Set default values
            $validated['user_id'] = auth()->id();
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Create FAQ
            $faq = Faq::create($validated);

            // Clear cache
            Faq::clearCache();

            return redirect()->route('admin.faqs.index')
                ->with('success', 'تم إنشاء السؤال الشائع بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error creating FAQ: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء السؤال الشائع: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the FAQ
     */
    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the FAQ
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string|max:50',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        try {
            // Set boolean values
            $validated['is_active'] = $request->has('is_active');
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Update FAQ
            $faq->update($validated);

            // Clear cache
            Faq::clearCache();

            return redirect()->route('admin.faqs.index')
                ->with('success', 'تم تحديث السؤال الشائع بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating FAQ: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث السؤال الشائع: ' . $e->getMessage());
        }
    }

    /**
     * Remove the FAQ
     */
    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();

            // Clear cache
            Faq::clearCache();

            return redirect()->route('admin.faqs.index')
                ->with('success', 'تم حذف السؤال الشائع بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting FAQ: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف السؤال الشائع: ' . $e->getMessage());
        }
    }

    /**
     * Toggle FAQ active status
     */
    public function toggleStatus(Faq $faq)
    {
        try {
            $faq->update(['is_active' => !$faq->is_active]);

            // Clear cache
            Faq::clearCache();

            $status = $faq->is_active ? 'تفعيل' : 'إلغاء تفعيل';
            return redirect()->route('admin.faqs.index')
                ->with('success', "تم {$status} السؤال الشائع بنجاح.");
        } catch (\Exception $e) {
            Log::error('Error toggling FAQ status: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تغيير حالة السؤال الشائع: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display all testimonials for public viewing
     */
    public function index()
    {
        $testimonials = Testimonial::visible()->ordered()->paginate(12);
        return view('testimonials.all', compact('testimonials'));
    }

    /**
     * Display a listing of testimonials
     */
    public function adminIndex()
    {
        $testimonials = Testimonial::with('user')->ordered()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created testimonial
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'story_content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('testimonials', 'public');
                $validated['image'] = $imagePath;
            }

            // Set default values
            $validated['user_id'] = auth()->id();
            $validated['is_visible'] = $request->has('is_visible') ? true : false;
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Create testimonial
            Testimonial::create($validated);

            // Clear cache
            Testimonial::clearCache();

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'تم إنشاء قصة النجاح بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error creating testimonial: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء قصة النجاح: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the testimonial
     */
    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the testimonial
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'story_content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($testimonial->image) {
                    Storage::disk('public')->delete($testimonial->image);
                }
                $imagePath = $request->file('image')->store('testimonials', 'public');
                $validated['image'] = $imagePath;
            }

            // Set boolean values
            $validated['is_visible'] = $request->has('is_visible') ? true : false;
            $validated['sort_order'] = $validated['sort_order'] ?? 0;

            // Update testimonial
            $testimonial->update($validated);

            // Clear cache
            Testimonial::clearCache();

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'تم تحديث قصة النجاح بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error updating testimonial: ' . $e->getMessage());
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث قصة النجاح: ' . $e->getMessage());
        }
    }

    /**
     * Remove the testimonial
     */
    public function destroy(Testimonial $testimonial)
    {
        try {
            // Delete image if exists
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }

            $testimonial->delete();

            // Clear cache
            Testimonial::clearCache();

            return redirect()->route('admin.testimonials.index')
                ->with('success', 'تم حذف قصة النجاح بنجاح.');
        } catch (\Exception $e) {
            Log::error('Error deleting testimonial: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف قصة النجاح: ' . $e->getMessage());
        }
    }

    /**
     * Toggle testimonial visibility
     */
    public function toggleVisibility(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_visible' => !$testimonial->is_visible]);

            // Clear cache
            Testimonial::clearCache();

            $status = $testimonial->is_visible ? 'إظهار' : 'إخفاء';
            return redirect()->route('admin.testimonials.index')
                ->with('success', "تم {$status} قصة النجاح بنجاح.");
        } catch (\Exception $e) {
            Log::error('Error toggling testimonial visibility: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تغيير حالة قصة النجاح: ' . $e->getMessage());
        }
    }
}
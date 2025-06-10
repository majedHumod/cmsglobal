<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        // تحديث الصلاحيات لتشمل page_manager
        $this->middleware(['auth', 'role:admin|page_manager'])->except(['show', 'publicIndex']);
    }

    public function index()
    {
        $pages = Page::with('user')
            ->when(!auth()->user()->hasRole('admin'), function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        return view('pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:160',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date'
        ]);

        // إنشاء slug من العنوان
        $validated['slug'] = Str::slug($validated['title']);
        
        // التأكد من أن الـ slug فريد
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // رفع الصورة المميزة
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('pages', 'public');
            $validated['featured_image'] = $imagePath;
        }

        // تعيين المستخدم الحالي
        $validated['user_id'] = auth()->id();
        
        // تعيين تاريخ النشر إذا كانت الصفحة منشورة
        if ($validated['is_published'] && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        Page::create($validated);

        return redirect()->route('pages.index')->with('success', 'تم إنشاء الصفحة بنجاح.');
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->hasRole('admin') && $page->user_id !== auth()->id()) {
            abort(403);
        }

        return view('pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->hasRole('admin') && $page->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable|max:160',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
            'published_at' => 'nullable|date'
        ]);

        // تحديث الـ slug إذا تغير العنوان
        if ($validated['title'] !== $page->title) {
            $newSlug = Str::slug($validated['title']);
            $originalSlug = $newSlug;
            $counter = 1;
            while (Page::where('slug', $newSlug)->where('id', '!=', $page->id)->exists()) {
                $newSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $newSlug;
        }

        // رفع صورة جديدة
        if ($request->hasFile('featured_image')) {
            // حذف الصورة القديمة
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('pages', 'public');
            $validated['featured_image'] = $imagePath;
        }

        // تعيين تاريخ النشر إذا كانت الصفحة منشورة لأول مرة
        if ($validated['is_published'] && !$page->is_published && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        $page->update($validated);

        return redirect()->route('pages.index')->with('success', 'تم تحديث الصفحة بنجاح.');
    }

    public function destroy(Page $page)
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->hasRole('admin') && $page->user_id !== auth()->id()) {
            abort(403);
        }

        // حذف الصورة المميزة
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('pages.index')->with('success', 'تم حذف الصفحة بنجاح.');
    }

    public function publicIndex()
    {
        $pages = Page::published()
            ->with('user')
            ->latest('published_at')
            ->paginate(12);

        return view('pages.public', compact('pages'));
    }
}
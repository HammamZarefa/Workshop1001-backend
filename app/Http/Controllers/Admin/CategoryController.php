<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('display_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->load('children');
        return view('admin.categories.show', compact('category'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::create($data);

        if ($request->hasFile('image_file')) {
            $category->addMedia($request->file('image_file'))
                ->toMediaCollection('categories');
        } elseif ($request->filled('image_url')) {
            $category->addMediaFromUrl($request->image_url)
                ->toMediaCollection('categories');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category Has Been Created Successfully');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $category->update($data);

        if ($request->hasFile('image_file')) {
            $category->clearMediaCollection('categories');
            $category->addMedia($request->file('image_file'))
                ->toMediaCollection('categories');
        } elseif ($request->filled('image_url')) {
            $category->clearMediaCollection('categories');
            $category->addMediaFromUrl($request->image_url)
                ->toMediaCollection('categories');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category Has Been Updated Successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category Has Been Deleted Successfully');
    }

    public function reorder(Request $request)
    {
        $items = $request->validate([
            'items' => ['required', 'array']
        ])['items'];

        foreach ($items as $item) {
            Category::where('id', $item['id'])->update([
                'display_order' => $item['order']
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category Has Been Reordered Successfully');
    }
}

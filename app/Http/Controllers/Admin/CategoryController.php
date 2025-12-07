<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;



class CategoryController extends Controller
{

    // عرض التصنيفات (صفحة)
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->orderBy('display_order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    // عرض صفحة كاتيجوري معيّن
    public function show($id)
    {
        $category = Category::with('children')->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }
// عرض نموذج إنشاء تصنيف جديد
public function create()
{
    $categories = Category::all();
    return view('admin.categories.create', compact('categories'));
}

    // إنشاء كاتيجوري جديد
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

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'category Has Been Created Successfully');
}

public function edit($id)
{
    $category = Category::findOrFail($id);

    $parents = Category::whereNull('parent_id')
                       ->where('id', '!=', $id)
                       ->get();

    return view('admin.categories.edit', compact('category', 'parents'));
}

    // تعديل كانيجوري
   public function update(CategoryRequest $request, $id)
{
    $data = $request->validated();

    $category = Category::findOrFail($id);
    $category->update($data);

    // لو رفع صورة من الجهاز
    if ($request->hasFile('image_file')) {
        $category->clearMediaCollection('categories');
        $category->addMedia($request->file('image_file'))
                 ->toMediaCollection('categories');
    }

    // لو وضع رابط صورة
    elseif ($request->filled('image_url')) {
        $category->clearMediaCollection('categories');
        $category->addMediaFromUrl($request->image_url)
                 ->toMediaCollection('categories');
    }

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Catrgory Has Been Updated Successfully');
}

    // حذف كاتيجوري
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Catrgory Has Been Deleted Successfully');
    }

    // إعادة الترتيب
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
                         ->with('success', 'Catrgory Has Been Reordered Successfully');
    }
}

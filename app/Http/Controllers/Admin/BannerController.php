<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Banner::class, 'banner');
    }

    public function index()
    {
        $banners = Banner::withoutGlobalScope('is_active')->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(BannerRequest $request)
    {
        $data = $request->validated();

        $banner = Banner::create($data);

        if ($request->hasFile('image')) {
            $banner->addMediaFromRequest('image')->toMediaCollection('banners');
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner Has Been Created Successfully');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        $data = $request->validated();

        $banner->update($data);

        if ($request->hasFile('image')) {
            $banner->clearMediaCollection('banners');
            $banner->addMediaFromRequest('image')->toMediaCollection('banners');
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner Has Been Updated Successfully');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner Has Been Deleted Successfully');
    }
}

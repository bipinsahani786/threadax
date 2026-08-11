<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->get();
        return view('admin.pages.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.pages.banners.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'position' => 'required|string|in:hero,mid,bottom',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'image' => 'required|image|max:2048',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['image_path'] = $request->file('image')->store('banners', 'public');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.pages.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'position' => 'required|string|in:hero,mid,bottom',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

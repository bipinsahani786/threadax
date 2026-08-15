<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $position = $request->query('position', 'all');
        $status = $request->query('status', 'all');

        $query = Banner::orderBy('position')->orderBy('sort_order');

        if ($position !== 'all' && in_array($position, ['hero', 'mid', 'bottom'])) {
            $query->where('position', $position);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $banners = $query->paginate(12)->withQueryString();

        $stats = [
            'total'    => Banner::count(),
            'active'   => Banner::where('is_active', true)->count(),
            'hero'     => Banner::where('position', 'hero')->count(),
            'mid_bottom' => Banner::whereIn('position', ['mid', 'bottom'])->count(),
        ];

        return view('admin.pages.banners.index', compact('banners', 'stats', 'position', 'status'));
    }

    public function create()
    {
        return view('admin.pages.banners.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'link'        => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'position'    => 'required|string|in:hero,mid,bottom',
            'sort_order'  => 'required|integer',
            'is_active'   => 'nullable',
            'image'       => 'required|image|max:4096',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['image_path'] = $request->file('image')->store('banners', 'public');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created and published successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.pages.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'link'        => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'position'    => 'required|string|in:hero,mid,bottom',
            'sort_order'  => 'required|integer',
            'is_active'   => 'nullable',
            'image'       => 'nullable|image|max:4096',
        ]);

        $data['is_active'] = $request->boolean('is_active', false);

        if ($request->hasFile('image')) {
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        $product->load(['variants', 'images' => fn($q) => $q->orderBy('sort_order')]);
        return view('admin.pages.products.variants', compact('product'));
    }

    public function storeVariants(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'variants' => 'array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $incomingVariants = $request->input('variants', []);
        $incomingVariantIds = collect($incomingVariants)->pluck('id')->filter()->toArray();

        // Delete variants that are no longer in the request
        $product->variants()->whereNotIn('id', $incomingVariantIds)->delete();

        // Update or Create variants
        foreach ($incomingVariants as $variantData) {
            $data = [
                'size' => $variantData['size'] ?? null,
                'color' => $variantData['color'] ?? null,
                'sku' => $variantData['sku'] ?? null,
                'price' => $variantData['price'] ?? null,
                'stock' => $variantData['stock'] ?? 0,
                'is_active' => isset($variantData['is_active']) ? (bool) $variantData['is_active'] : false,
            ];

            if (!empty($variantData['id'])) {
                $product->variants()->where('id', $variantData['id'])->update($data);
            } else {
                $product->variants()->create($data);
            }
        }

        return back()->with('success', 'Product variants updated successfully.');
    }

    public function storeImages(Request $request, Product $product)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB per image
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Store in public/products
                $path = $file->store('products', 'public');

                // If no images exist, make this one primary
                $isPrimary = $product->images()->count() === 0;

                $product->images()->create([
                    'url' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => 0,
                ]);
            }
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    public function setPrimaryImage(ProductImage $image)
    {
        // Remove primary flag from all other images of this product
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        
        // Set this one as primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }

    public function deleteImage(ProductImage $image)
    {
        // Delete physical file from storage
        $rawUrl = $image->getRawOriginal('url') ?? $image->url;
        $path = ltrim(parse_url($rawUrl, PHP_URL_PATH) ?? $rawUrl, '/');
        while (str_starts_with($path, 'storage/')) {
            $path = ltrim(substr($path, 8), '/');
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}

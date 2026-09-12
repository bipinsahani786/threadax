<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Setting;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'is_active', 'is_featured', 'stock_status']);
        
        $query = Product::with(['category', 'variants', 'images']);

        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['category'])->orWhere('id', $filters['category']);
            });
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }

        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'out_of_stock') {
                $query->whereDoesntHave('variants', fn($q) => $q->where('stock', '>', 0));
            } elseif ($filters['stock_status'] === 'low_stock') {
                $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5));
            } elseif ($filters['stock_status'] === 'in_stock') {
                $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0));
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        
        // Analytics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $outOfStockCount = Product::whereDoesntHave('variants', fn($q) => $q->where('stock', '>', 0))->count();
        $lowStockCount = Product::whereHas('variants', fn($q) => $q->where('stock', '>', 0)->where('stock', '<=', 5))->count();

        // Calculate Total Inventory Valuation
        $totalValuation = \App\Models\ProductVariant::join('products', 'product_variants.product_id', '=', 'products.id')
            ->selectRaw('SUM(product_variants.stock * COALESCE(product_variants.price, products.price)) as total_val')
            ->value('total_val') ?? 0;

        $stats = [
            'total' => $totalProducts,
            'active' => $activeProducts,
            'featured' => $featuredProducts,
            'out_of_stock' => $outOfStockCount,
            'low_stock' => $lowStockCount,
            'total_valuation' => (float) $totalValuation,
        ];

        // For filter dropdown
        $categories = Category::orderBy('name')->get();

        return view('admin.pages.products.index', compact('products', 'stats', 'categories', 'filters'));
    }

    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate(['slug']);
        $newProduct->name = $product->name . ' (Copy)';

        $baseSku = $product->sku ? $product->sku . '-COPY' : null;
        if ($baseSku) {
            $candidateSku = $baseSku;
            $counter = 1;
            while (Product::where('sku', $candidateSku)->exists()) {
                $candidateSku = $baseSku . '-' . $counter++;
            }
            $newProduct->sku = $candidateSku;
        } else {
            $newProduct->sku = null;
        }

        $newProduct->is_active = false;
        $newProduct->save();

        // Duplicate Variants
        foreach ($product->variants as $variant) {
            $newVariant = $variant->replicate();
            $newVariant->product_id = $newProduct->id;
            if ($variant->sku) {
                $candidateVariantSku = $variant->sku . '-COPY';
                $counter = 1;
                while (\App\Models\ProductVariant::where('sku', $candidateVariantSku)->exists()) {
                    $candidateVariantSku = $variant->sku . '-COPY-' . $counter++;
                }
                $newVariant->sku = $candidateVariantSku;
            } else {
                $newVariant->sku = null;
            }
            $newVariant->save();
        }

        // Duplicate Images
        foreach ($product->images as $image) {
            $newImage = $image->replicate();
            $newImage->product_id = $newProduct->id;
            $newImage->save();
        }

        return redirect()->route('admin.products.edit', $newProduct)
            ->with('success', 'Product duplicated successfully as draft. You can now edit and publish it.');
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.pages.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:255',
            'model_3d_url' => 'nullable|url|max:255',
            'size_guide_url' => 'nullable|url|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Name slug is handled by HasSlug trait

        $this->productRepository->create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.pages.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'video_url' => 'nullable|url|max:255',
            'model_3d_url' => 'nullable|url|max:255',
            'size_guide_url' => 'nullable|url|max:255',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $this->productRepository->update($product, $validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->variants()->exists() || $product->images()->exists()) {
            // Note: In real app, we might want to cascade delete or use soft deletes (already using soft deletes on product)
        }
        
        $this->productRepository->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Generate rich product copy & SEO with Gemini 1.5 Flash
     */
    public function generateAiContent(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'rough_notes' => 'nullable|string|max:2000',
            'category_id' => 'nullable',
            'tone'        => 'nullable|string',
        ]);

        if (!$geminiService->hasApiKey()) {
            return response()->json([
                'success'      => false,
                'requires_key' => true,
                'message'      => 'Google Gemini API key is missing. Please enter your free API key to continue.',
            ], 422);
        }

        $categoryName = null;
        if ($request->filled('category_id')) {
            $cat = Category::find($request->category_id);
            if ($cat) {
                $categoryName = $cat->name;
            }
        }

        try {
            $generated = $geminiService->generateProductContent(
                name: $request->name,
                roughNotes: $request->rough_notes,
                category: $categoryName,
                tone: $request->tone ?? 'streetwear_bold'
            );

            return response()->json([
                'success' => true,
                'data'    => $generated,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate an on-model photoshoot using Google Imagen 3 and Gemini Multimodal
     */
    public function generateAiImage(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'product_name'    => 'required|string|max:255',
            'rough_notes'     => 'nullable|string|max:1000',
            'reference_image' => 'nullable|string', // base64
            'model_style'     => 'nullable|string',
            'product_id'      => 'nullable|exists:products,id',
        ]);

        if (!$geminiService->hasApiKey()) {
            return response()->json([
                'success'      => false,
                'requires_key' => true,
                'message'      => 'Google Gemini API key is missing. Please enter your free API key in Store Settings.',
            ], 422);
        }

        try {
            $result = $geminiService->generateModelPhotoshoot(
                productName: $request->product_name,
                roughNotes: $request->rough_notes,
                referenceImageBase64: $request->reference_image,
                modelStyle: $request->model_style ?? 'male_streetwear'
            );

            // If product_id is provided, automatically attach as ProductImage
            if ($request->filled('product_id')) {
                $product = Product::findOrFail($request->product_id);
                $isPrimary = $product->images()->count() === 0;

                $productImage = $product->images()->create([
                    'url'        => $result['relative_path'],
                    'is_primary' => $isPrimary,
                    'sort_order' => $product->images()->count(),
                ]);

                $result['product_image_id'] = $productImage->id;
            }

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Quick save Gemini API Key directly from AI modal
     */
    public function saveGeminiKey(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string|min:15',
        ]);

        Setting::updateOrCreate(
            ['key' => 'gemini_api_key'],
            ['value' => trim($request->api_key)]
        );

        return response()->json([
            'success' => true,
            'message' => 'Gemini API Key saved successfully!',
        ]);
    }
}

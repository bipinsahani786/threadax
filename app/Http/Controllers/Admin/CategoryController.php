<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'parent_id']);
        
        $categories = $this->categoryRepository->paginateAdmin($filters, 15);
        
        // Analytics
        $stats = [
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'roots' => Category::whereNull('parent_id')->count(),
        ];

        // For filter dropdown
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.pages.categories.index', compact('categories', 'stats', 'parentCategories', 'filters'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.pages.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_header'] = $request->has('show_in_header');
        $validated['sort_order'] = $request->input('sort_order', 0);

        // Name slug is handled by HasSlug trait

        $this->categoryRepository->create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id) // Cannot be parent of itself
            ->orderBy('name')
            ->get();
            
        return view('admin.pages.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_header'] = $request->has('show_in_header');
        $validated['sort_order'] = $request->input('sort_order', 0);

        // Prevent circular reference
        if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Category cannot be its own parent.']);
        }

        $this->categoryRepository->update($category, $validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete category with child categories.']);
        }
        
        if ($category->products()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete category with associated products.']);
        }

        $this->categoryRepository->delete($category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}

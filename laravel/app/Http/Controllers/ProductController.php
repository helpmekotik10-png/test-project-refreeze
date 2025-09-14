<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\GroupService;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function showProduct($id, GroupService $groupService): View
    {
        
        $product = Product::with('price', 'group')->findOrFail($id);

        
        $breadcrumbs = $groupService->getBreadcrumbsForGroup($product->id_group);

        
        $breadcrumbs[] = ['id' => null, 'name' => $product->name];

        return view('catalog.product', compact('product', 'breadcrumbs'));
    }
}
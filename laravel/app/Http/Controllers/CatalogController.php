<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

use App\Models\Group;
use App\Models\Product;
use App\Services\GroupService;

class CatalogController extends Controller
{
    public function index(Request $request, GroupService $groupService): View 
    {
        $topGroups = $groupService->getTopGroups();
        
        $query = Product::with('price')->has('price');
        $sort = $request->get('sort', 'name_asc'); 
        $groupService->applySorting($query, $sort); 

        $perPage = $request->get('per_page', 12);
        $perPage = in_array($perPage, [6, 12, 18]) ? $perPage : 12;

        $products = $query->paginate($perPage);

        return view('catalog.index', compact('topGroups', 'products', 'sort', 'perPage'));
    }

    public function showGroup($id, Request $request, GroupService $groupService): View 
    {
        
        $group = Group::findOrFail($id);
        $subgroups = $group->subgroups;
        
        foreach ($subgroups as $sub) {
            $allIds = $groupService->getAllSubgroupIds($sub->id);
            $allIds[] = $sub->id;
            $sub->product_count = Product::whereIn('id_group', $allIds)->count();
        }

        $allGroupIds = $groupService->getAllSubgroupIds($id);
        $allGroupIds[] = $id;

        $query = Product::with('price')
                        ->whereIn('id_group', $allGroupIds)
                        ->has('price');

        $sort = $request->get('sort', 'name_asc');
        $groupService->applySorting($query, $sort);

        $perPage = $request->get('per_page', 12);
        $perPage = in_array($perPage, [6, 12, 18]) ? $perPage : 12;

        $products = $query->paginate($perPage);

        $breadcrumbs = $groupService->getBreadcrumbsForGroup($id);

        return view('catalog.group', compact(
            'group', 
            'subgroups', 
            'products', 
            'sort', 
            'breadcrumbs',
            'perPage'
        ));
    }

}

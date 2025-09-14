<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class GroupService
{
    public function getAllSubgroupIds (int $parentId): array 
    {
        $ids = [];
        $stack = [$parentId];

        while (!empty($stack)) {
            $current = array_pop($stack);
            $children = Group::where('id_parent', $current)->pluck('id')->toArray();
            $ids = array_merge($ids, $children);
            $stack = array_merge($stack, $children);
        }

        return $ids;
    }

    public function getTopGroups(): Collection
    {
        $topGroups = Group::where('id_parent', 0)->get();

        foreach ($topGroups as $group) {
            $subgroupIds = $this->getAllSubgroupIds($group->id);
            $allIds = array_merge($subgroupIds, [$group->id]);
            $group->product_count = Product::whereIn('id_group', $allIds)->count();
        }

        return $topGroups;
    }

    public function applySorting($query, $sort): void {
        switch ($sort) {
            case 'price_asc':
                $query->orderBy(function ($q) {
                    $q->select('price')
                    ->from('prices')
                    ->whereColumn('prices.id_product', 'products.id');
                }, 'asc');
                break;

            case 'price_desc':
                $query->orderBy(function ($q) {
                    $q->select('price')
                    ->from('prices')
                    ->whereColumn('prices.id_product', 'products.id');
                }, 'desc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->orderBy('name', 'asc');
        }
    }

    public function getBreadcrumbsForGroup(int $groupId): array
    {
        $breadcrumbs = [];
        $currentId = $groupId;

        while ($currentId != 0) {
            $group = Group::find($currentId);
            if (!$group) break;

            // Вставляем в начало (чтобы порядок был от главной к текущей)
            array_unshift($breadcrumbs, [
                'id' => $group->id,
                'name' => $group->name
            ]);

            $currentId = $group->id_parent;
        }

        return $breadcrumbs;
    }
    
}
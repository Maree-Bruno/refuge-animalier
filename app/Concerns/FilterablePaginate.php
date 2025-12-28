<?php

namespace App\Concerns;
use Illuminate\Http\Request;

trait FilterablePaginate
{
    public function filterAndPaginate($model, Request $request, $relations = [], $searchParam = 'search', $perPage = 10)
    {
        $orderBy = $request->get('orderby', 'name');
        $dir     = $request->get('dir', 'asc');
        $search  = $request->get($searchParam, '');
        $status  = $request->get('status', 'all');

        $query = $model::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        $query->when($status !== 'all', function ($q) use ($status) {
            if ($status === 'Adopted') {
                $q->where('status', 'Adopted');
            } elseif ($status === 'refuge') {
                $q->whereIn('status', ['Validated', 'In progress']);
            }
        });

        $query->when($search, function ($q) use ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('chip', 'like', "%{$search}%");
            });
        });

        $query->orderBy($orderBy, $dir);
        return $query->paginate($perPage)->withQueryString();
    }
}

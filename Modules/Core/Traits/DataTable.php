<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Schema;

trait DataTable
{
    // DataTable Methods
    public static function drawTable($request, $query, $table = '')
    {
        $sort['col'] = $request->input('columns.' . $request->input('order.0.column') . '.data');
        $sort['dir'] = $request->input('order.0.dir');
        $search      = $request->input('search.value');

        $counter = $query->count();

        if (Schema::hasColumn($table, 'total')) {
            $output['recordsTotalSum'] = $query->sum('total');
        }

        $output['recordsTotal']    = $counter;
        $output['recordsFiltered'] = $counter;
        $output['draw']            = intval($request->input('draw'));

        // Get Data
        $models = $query
            ->orderBy($sort['col'], $sort['dir'])
            ->skip($request->input('start'))
            ->take($request->input('length', 25))
            ->get();

        $output['data']  = $models;

        return $output;
    }
}

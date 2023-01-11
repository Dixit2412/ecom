<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('shop_id', function($row){
                return $row->shop->name ?? null;
            })
            ->addColumn('action', function ($row) {
                return $this->checkRights($row);
            })->rawColumns(['action'])
            ->make(true);
    }


    public function checkRights($row)
    {
        $menu = '';
        $editUrl = route('product.edit', ['product' => $row]);
        $deleteUrl = route('product.destroy', ['product' => $row]);

        $menu .= '<a class="btn btn-primary btn-sm" href="' . $editUrl . '" role="button">Edit</a>';

        $menu .= '&nbsp;&nbsp;<a class="btn btn-danger btn-sm action_confirm" href="' . $deleteUrl . '" data-method="delete" data-modal-text=" <b>' . $row->name . '</b> Product?" data-original-title="Delete Role" title="Delete"> Delete</a>';

        return $menu;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Year $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $models = Product::with(['shop'])->select();
        if (request()->get('name', false)) {
            $models->where('name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->get('price', false)) {
            $models->where('price', 'like', '%' . request()->get('price') . '%');
        }
        if (request()->get('stock', false)) {
            $models->where('is_stock', '=', request()->get('stock'));
        }
        if (request()->get('shop_id', false)) {
            $models->whereHas('shop', function ($query) {
                $query->where('name', 'like', '%' . request()->get('shop_id') . '%');
            });
        }

        if (!request()->has('order')) {
            $models->orderBy('product.id', 'DESC');
        }
        return $this->applyScopes($models);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->parameters(['searching' => false])
            ->columns($this->getColumns())
            ->ajax('');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            Column::make('name')
                ->title('Name')
                ->width(50)
                ->addClass('text-center'),
            Column::make('code')
                ->title('Code')
                ->width(50)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Shop_' . date('YmdHis');
    }
}

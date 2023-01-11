<?php

namespace App\DataTables;

use App\Models\Shop;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ShopDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($row) {
                return $this->checkRights($row);
            })->rawColumns(['action'])
            ->make(true);
    }


    public function checkRights($row)
    {
        $menu = '';
        $editUrl = route('shop.edit', ['shop' => $row]);
        $deleteUrl = route('shop.destroy', ['shop' => $row]);

        $menu .= '<a class="btn btn-primary btn-sm " href="' . $editUrl . '" role="button">Edit</a>';

        $menu .= '<a class="btn btn-danger btn-sm action_confirm" href="' . $deleteUrl . '" data-method="delete" data-modal-text=" <b>' . $row->name . '</b> Shop?" data-original-title="Delete Role" title="Delete"> Delete</a>';

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
        $models = Shop::select();
        if (request()->get('email', false)) {
            $models->where('email', 'like', '%' . request()->get('email') . '%');
        }
        if (request()->get('name', false)) {
            $models->where('name', 'like', '%' . request()->get('name') . '%');
        }

        if (!request()->has('order')) {
            $models->orderBy('shop.id', 'DESC');
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

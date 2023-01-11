<?php

namespace App\DataTables;

use App\Models\States;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Sentinel;

class StatesDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     *
     */
    public function ajax()
    {
        $user = Sentinel::getUser();
        $userUpdate = $user->hasAccess('states.update');
        $userDelete = $user->hasAccess('states.delete');
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($row) use($userUpdate, $userDelete) {
                return $this->checkRights($row,$userUpdate, $userDelete);
            })
            ->editColumn('countries_id', function ($row) {
                return $row->country->name ?? null;
            })->rawColumns(['action'])
            ->make(true);
    }

    public function checkRights($row,$userUpdate, $userDelete)
    {
        $menu = '';
        $editUrl = route('states.edit', ['state' => $row->id]);
        $deleteUrl = route('states.destroy', ['state' => $row->id]);

        if ($userUpdate || $userDelete) {
            $menu .= '<div class="btn btn-primary dropdown-toggle btn-xs" id="dropdown-default-primary-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list"></i></div>';
            $menu .= '<div class="dropdown-menu dropdown-menu-right font-size-sm" aria-labelledby="dropdown-default-primary-' . $row->id . '">';

            if ($userUpdate) {
                $menu .= '<a class="dropdown-item edit-link text-info" href="' . $editUrl . '" data-show-edit-modal="'.$row->id.'" title="Edit"><i class="fas fa-edit mr-2"></i> ' . trans('comman.edit') . '</a>';
            }

            if ($userDelete) {
                $menu .= '<a class="dropdown-item action_confirm text-danger" href="' . $deleteUrl . '" data-method="delete" data-modal-text=" <b>' . $row->name . '</b> ' . trans('comman.state') . '?" data-original-title="Delete Role" title="Delete"><i class="fas fa-trash-alt mr-2"></i> Delete</a>';
            }
            $menu .= '</div>';
        }

        return $menu;
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\State $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $models = States::with(['country']);

        if (request()->get('countries_name', false)) {
            $models->whereHas('country', function ($query) {
                $query->where('name', "like", "%" . request()->get("countries_name") . "%");
            });
        }
        if (request()->get('name', false)) {
            $models->where('name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->get('gst_code', false)) {
            $models->where('gst_code', 'like', '%' . request()->get('gst_code') . '%');
        }
        if (!request()->has('order')) {
            $models->orderBy('states.id', 'ASC');
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

            Column::make('countries_id')
                ->title('Countryname')
                ->width(50)
                ->addClass('text-center'),

            Column::make('name')
                ->title('stateName')
                ->width(50)
                ->addClass('text-center'),

            Column::make('gst_code')
                ->title('gst_code')
                ->width(50)
                ->addClass('text-center')

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'States_' . date('YmdHis');
    }
}

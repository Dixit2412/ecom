<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Common\CommonDataTable;
use Sentinel;
use Html;
use DB;
use Localization;
use Crypt;

class UserDatatable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('action', function ($row) {
                return $this->checkrights($row);
            })
            ->editColumn('id',function($row){
                return $row->first_name.' '.$row->last_name;
            })
            ->editColumn('role',function($row){
                if($row){
                    return $row['role'];
                }
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function checkrights($row)
    {
        $user = Sentinel::getUser();
        $menu = '';
        $edit_url = route('users.edit', ['id' => $row->id,'_url' =>route('users.index')]);
        $delete_url = route('users.destroy', ['id' => $row->id]);
        $status_url = route('users.status', ['id' => $row->id]);
        $auto_login = route('users.auto_login',['id' => Crypt::encrypt($row->id)]);
        $menu .='<div class="btn-group">
                <button type="button" class="btn btn-xs btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-list-ul"></i><span class="caret"></span></button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">';
        if ($user->hasAccess(['users.update'])) {
            $menu .= '<a class="dropdown-item edit-link text-info" href="' . $edit_url . '" title="'.trans("comman.edit").'"><i class="fas fa-edit mr-2"></i> '.trans("comman.edit") .'</a>';
        }
        if ($user->hasAccess(['users.auto_login']) && $row->id !== $user->id) {
            $menu .= '<a class="dropdown-item text-primary" href="' . $auto_login . '" title="Auto-Login"><i class="fas fa-user-lock mr-2"></i> Auto-Login</a>';
        }
        $menu.="</div>";

        return $menu;
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $userRepository = app()->make('sentinel.users');
        $models = $userRepository->createModel()->with('activations')->whereHas('activations',function($query){
            $query->where('completed',1);
        })->with('UsersRole.roleName');
        $models->select(['users.*',DB::raw('GROUP_CONCAT(DISTINCT roles.name) as role')])
        ->leftJoin("role_users", function ($join) {
            $join->on("role_users.user_id", "=", "users.id");
        })
        ->leftJoin("roles", function ($join) {
            $join->on("roles.id", "=", "role_users.role_id");
        });

        if (request()->get('id', false)) {
            $models->where(DB::raw('CONCAT(users.first_name," ",users.last_name)'),'like', "%" . request()->get("id") . "%");
        }
        if (request()->get('email', false)) {
            $models->where('users.email', 'like', "%" . request()->get("email") . "%");
        }
        if (request()->get('mobile_no', false)) {
            $models->where('users.mobile_no', 'like', "%" . request()->get("mobile_no") . "%");
        }
        if (request()->get('role', false)) {
            $models->whereHas('UsersRole.roleName',  function ($q){
                   $q->where('roles.name','like', "%" . request()->get("role") . "%");
            });
        }
        $models = $models->groupBy('users.id');
        if (!request()->has('order')) {
            $models->orderBy('id', 'DESC');
        }
        return $this->applyScopes($models);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                ->parameters(['searching' => false])
                ->columns($this->getColumns())
                ->parameters($this->getBuilderParameters())
                ->ajax('');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            ['data'=>'image','name'=> 'image', 'title'=>trans("comman.Preview"),'render'=> null,'searchable'=> false,'orderable'=> false],
            ['data'=>'id','name'=> 'id', 'title'=>trans("comman.name")],
            ['data'=>'email','name'=> 'email','title' => trans("comman.email")],
            ['data'=>'mobile_no','name'=> 'mobile_no','title' => trans("comman.mobile")],
            ['data'=>'status','name'=> 'status','title' => trans("comman.status")],
            ['data'=>'action','name'=> 'action','title'=> trans("comman.action"),'render'=> null,'orderable'=> false,'searchable'=> false,'exportable'=> false,'printable'=> true,'footer'=> '','width' => '100px'],
        ];

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'User';
    }

    protected function getBuilderParameters()
    {
        return [
            'drawCallback' => 'function () {
               jQuery(this).find("tbody tr").slice(-3).find(".dropdown, .btn-group").addClass("dropup");
            }',
            'preDrawCallback' => 'function () {
               jQuery(this).find("tbody tr").slice(-3).find(".dropdown, .btn-group").removeClass("dropup");
            }',
            'order' => [[1, 'desc']]
        ];
    }

    private function getFilterColumns()
    {
        return ['id','email','status','image','mobile_no'];
    }

}

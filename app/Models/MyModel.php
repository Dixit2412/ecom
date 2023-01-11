<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    protected $table;
    protected $dependency;

    public function deleteValidate($id)
    {
        $msg = [];
        if (!is_null($this->dependency) && !is_null($id)) {
            foreach ($this->dependency as $key => $row) {
                $row = (object) $row;
                $model = app()->make($row->table);
                if (isset($row->csv) && $row->csv) {
                    if ($model->whereRaw("find_in_set('" . $id . "'," . $row->field . ')')->count()) {
                        $msg[] = $key;
                    }
                } elseif ($model->where($row->field, $id)->count()) {
                    $msg[] = $key;
                }
            }
            if (!is_null($msg)) {
                $msg = implode(', ', $msg);
            }
        }
        return $msg;
    }

    public function deleteAllRecord($ids = [], $name_list = []) {
        $msg = [];
        if (!empty($this->dependtables)) {
            foreach ($this->dependtables as $k => $row) {
                $row = (object) $row;
                $results = DB::table($row->table)
                        ->whereNull("deleted_at")
                        ->select('*')
                        ->whereIN("{$row->field}", $ids)
                        ->groupBy("{$row->field}")
                        ->get();

                if (count($results)) {
                    foreach ($results as $value) {
                        $field_name = $row->field;
                        if(isset($name_list[$value->$field_name]) && !empty($name_list[$value->$field_name])){
                            $msg[] = $name_list[$value->$field_name]. ' use in ' .$k;
                        }
                    }
                }
            }
        }
        if (!empty($msg)) {
            $msg = implode(", </br>", $msg);
        }
        return $msg;
    }
}

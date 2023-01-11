<?php

namespace App\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends MyModel
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'email', 'address', 'image', 'deleted_at'];

    protected $table = "shop";

    protected $dependency = [];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function SetNameAttribute($value)
    {
        if (!empty($value)) {
            return $this->attributes['name'] = strtoupper($value);
        }
    }
}

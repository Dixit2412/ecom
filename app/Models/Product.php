<?php

namespace App\Models;

use App\Models\MyModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends MyModel
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['shop_id', 'name', 'price', 'is_stock', 'image', 'deleted_at'];

    protected $table = "product";

    protected $dependency = [];

    public function shop()
    {
        return $this->belongsTo('App\Models\Shop', 'shop_id', 'id');
    }
}

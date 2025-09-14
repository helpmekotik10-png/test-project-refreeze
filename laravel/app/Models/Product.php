<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\Price;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ProductFactory;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    public function price()
    {
        return $this->hasOne(Price::class, 'id_product');
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}

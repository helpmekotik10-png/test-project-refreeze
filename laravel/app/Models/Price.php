<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\PriceFactory;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    protected static function newFactory()
    {
        return PriceFactory::new();
    }
}

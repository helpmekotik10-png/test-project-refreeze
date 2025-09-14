<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(Group::class, 'id_parent');
    }

    public function subgroups()
    {
        return $this->hasMany(Group::class, 'id_parent');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_group');
    }

    protected static function newFactory()
    {
        return GroupFactory::new();
    }
}

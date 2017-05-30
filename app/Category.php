<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    // Para usar softDeletes hay que indicar que el campo es de tipo date y protegido para uso interno
    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description'];
    
    public function products() {
        return $this->belongsToMany(Product::class);
    }
}

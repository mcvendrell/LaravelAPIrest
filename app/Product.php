<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const AVAILABLE = 'disponible';
    const NOT_AVAILABLE = 'no disponible';
    
    // Para usar softDeletes hay que indicar que el campo es de tipo date y protegido para uso interno
    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'quantity', 'status', 'image','seller_id'];
    
    public function isAvailable() {
        return $this->status == Product::AVAILABLE;
    }

    public function seller() {
        return $this->belongsTo(Seller::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }
    
}

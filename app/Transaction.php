<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    // Para usar softDeletes hay que indicar que el campo es de tipo date y protegido para uso interno
    protected $dates = ['deleted_at'];

    protected $fillable = ['quatity','buyer_id','product_id'];
    
    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
}

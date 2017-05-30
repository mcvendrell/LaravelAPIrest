<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const USER_VERIFIED = '1';
    const USER_NOT_VERIFIED = '0';
    
    const USER_ADMIN = 'true';
    const USER_NORMAL = 'false';
    
    // Debido a nuestor peculiar diseño de tablas, Seller y Buyer extienden de User y usan su tabla. Pero al no tener más definicion
    // Laravel intenta usar su nombre de tabla automáticamente, y no existe tabla sellers ni buyers.
    // Hay que explicitar directamente que la tabla es users, para que ambos hereden ese nombre como nombre de tabla y no se intente inferir
    protected $table = 'users';
    // Para usar softDeletes hay que indicar que el campo es de tipo date y protegido para uso interno
    protected $dates = ['deleted_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verified', 'verification_token', 'admin'
    ];

    // Mutador para pasar a minúsculas antes de grabar en BD
    public function setNameAttribute($value) {
        $this->attributes['name'] = strtolower($value);
    }
    public function setEmailAttribute($value) {
        $this->attributes['email'] = strtolower($value);
    }

    // Accesor para capitalizar el nombre al leerlo de la BD, antes de mostrarlo
    public function getNameAttribute($value) {
        return ucwords($value);
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token'
    ];
    
    public function isVerified() {
        return $this->verified == User::USER_VERIFIED;
    }

        public function isAdmin() {
        return $this->admin == User::USER_ADMIN;
    }
    
    public static function generateVerificationToken() {
        return str_random(40);
    }
    
}

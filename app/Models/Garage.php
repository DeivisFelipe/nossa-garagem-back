<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'garages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cep', 'state', 'city', 'neighborhood', 'street', 'number', 'complement'];

    public function user(){
        return $this->hasOne(User::class);
    }

}

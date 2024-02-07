<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalTeam extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lang',
        'federation',
        'flag_image_path',
        'status',
        'llave_octavos',
        'llave_cuartos',
        'llave_semi',
        'llave_tercero',
        'llave_final',
        'position',
        'pos_grupos'
    ];

    protected $hidden = [
        'status',
        'llave_octavos',
        'llave_cuartos',
        'llave_semi',
        'llave_tercero',
        'llave_final',
        'position',
        'pos_grupos'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function players(){
        return $this->hasMany(Player::class);
    }

    public function group(){
        return $this->hasMany(Group::class);
    }
}

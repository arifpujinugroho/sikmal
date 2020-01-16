<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function operator()
    {
        return $this->hasOne('App\Model\Operator', 'id_user');
    }

    public function dosen()
    {
        return $this->hasOne('App\Model\Dosen', 'email_dosen');
    }

    public function perekap()
    {
        return $this->hasOne('App\Model\Perekap', 'id_user');
    }

    public function identitas_mahasiswa()
    {
        return $this->hasOne('App\Model\IdentitasMahasiswa', 'id_user');
    }
}

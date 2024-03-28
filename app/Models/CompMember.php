<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Notifications\Comp\PasswordResetNotification;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompMember extends Authenticatable
{

    use Notifiable;
	use SoftDeletes;

 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $guarded = [
        'id',
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
    
      /**
     * �ѥ���ɥꥻ�å����Τ������򥪡��С��饤��
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
      $this->notify(new PasswordResetNotification($token));
    }
}

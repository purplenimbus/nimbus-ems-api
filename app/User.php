<?php

namespace App;

use Webpatser\Uuid\Uuid as Uuid;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname','lname', 'email', 'dob','sex','image_url','user_id','meta'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password','id'
    ];
	
	public function tasks()
    {
        return $this->hasMany('App\Task');
    }

	
	/**
     * Cast meta property to array
     *
     * @var array
     */
	 
	protected $casts = [
        'meta' => 'array',
    ];
	
	/**
	 *  Setup model event hooks
	 */
	public static function boot()
	{
		parent::boot();
		self::creating(function ($model) {
			$model->uuid = (string) Uuid::generate(4);
		});
	}
	
}

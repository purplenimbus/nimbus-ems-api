<?php

namespace App;

use Webpatser\Uuid\Uuid as Uuid;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class CompanyPayroll extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companyPayrolls';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id','payroll_id', 'meta'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
		'id'
    ];
	
	
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

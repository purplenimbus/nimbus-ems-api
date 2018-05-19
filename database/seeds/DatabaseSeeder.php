<?php

use Illuminate\Database\Seeder;
use Illuminate\Hashing\BcryptHasher;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin 	=	factory(App\User::class, 1)->create([
						'tenant_id' => 1,
						'image_url' =>	'https://www.victoria147.com/wp-content/uploads/2014/10/user-avatar-placeholder.png',
						'fname'		=>	'anthony',
						'lname'		=>	'akpan',
						'email'		=>	'anthony.akpan@hotmail.com',
						'password'	=>	app('hash')->make('easier')
					])->each(function($user){
						
						factory(App\Activity::class,5)
							->create([ 
								'user_id' => $user->id,
								'tenant_id' => $user->tenant_id,
							]);
							
					});
					
		$records = factory(App\Tenant::class, 1)
			->create()
			->each(function($tenant){
				$companyPayroll = factory(App\CompanyPayroll::class,1)
					->create([ 
						'tenant_id' => $tenant->id
					]);
							
				factory(App\User::class,10)
					->create([
						'tenant_id' => $tenant->id,
						'image_url' =>	'https://www.victoria147.com/wp-content/uploads/2014/10/user-avatar-placeholder.png',
						//'meta'	=> [ "user_type" => "student" , "business_unit" => "school" ]
					])
					->each(function($user)use($tenant,$companyPayroll){
												
						factory(App\Payroll::class,1)
							->create([ 
								'user_id' => $user->id,
								'company_payroll_id' => $companyPayroll->id,
								'amount' => 50000
							]);
						
						factory(App\Activity::class,5)
							->create([ 
								'user_id' => $user->id,
								'tenant_id' => $tenant->id,
							]);
						
						
							
					});
					
			});
    }
}

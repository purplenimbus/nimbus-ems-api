<?php

namespace App\Jobs;

use App\Tenant as Tenant;
use App\CompanyPayroll as CompanyPayroll;
use App\Payroll as Payroll;
use App\User as User;

class GeneratePayroll extends Job
{
    protected $payroll_id;
    protected $tenant_id;
    protected $data;
	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant_id = false,$payroll_id = false,$data = false)
    {
		$this->tenant_id = $tenant_id ? $tenant_id : false;
		
		$this->payroll_id = $payroll_id ? $payroll_id : false;
		
		$this->data = $data ? $data : false;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		//create new company payroll
		$companyPayroll = new CompanyPayroll;
		
		$companyPayroll->tenant_id = $this->tenant_id;
		
		$companyPayroll->save();
		
		//assign users to payroll
		$users = User::where('tenant_id',$this->tenant_id)->get()->each(function($user)use($companyPayroll){
			//create new payroll
			$payroll = new Payroll;
			
			$payroll->company_payroll_id = $companyPayroll->id;
			
			$payroll->user_id = $user->id;
			
			$payroll->save();
		});
		
		//return something on success
		var_dump(Payroll::where('company_payroll_id',$companyPayroll->id)->get(['user_id','amount','uuid','complete'])->all());
    }
}

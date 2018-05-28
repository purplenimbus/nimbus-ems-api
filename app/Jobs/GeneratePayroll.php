<?php

namespace App\Jobs;

use App\Payroll;
use App\Paystack\PaystackBatch as PaystackBatch;

class GeneratePayroll extends Job
{
    protected $payroll_id;
    private $paytstack_batch;
	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		//get all tenants that have payrolls
		
		//add to companypayrolls table
		
		//add users to payroll table
    }
}

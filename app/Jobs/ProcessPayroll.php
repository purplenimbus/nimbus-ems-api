<?php

namespace App\Jobs;

use App\Payroll;
use App\Paystack\PaystackBatch as PaystackBatch;

class ProcessPayroll extends Job
{
    protected $payroll_id;
    private $paytstack_batch;
	/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payroll_id)
    {
        $this->payroll_id = $payroll_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		//$this->paytstack_batch = new PaystackBatch();
		
		/*$payroll = Payroll::where([
				['company_payroll_id', '=', $this->payroll_id],
		])->get(['uuid','amount']);
			
		$batch = $this->paytstack_batch->initiateBulkCharge($payroll->toArray());
	
		->update(['complete' => 1]);///->get(['uuid','amount','user_id','complete']);
		*/
    }
}

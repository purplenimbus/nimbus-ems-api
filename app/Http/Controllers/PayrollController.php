<?php

namespace App\Http\Controllers;

use App\User as User;
use App\Payroll as Payroll;
use App\CompanyPayroll as CompanyPayroll;
use Yabacon\Paystack as Paystack;
use App\Jobs\ProcessPayroll;

use Illuminate\Http\Request;

class PayrollController extends Controller
{
    var $paystack;
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->paystack = new Paystack(env('PAYSTACK_SECRET_KEY'));
    }

	/**
     * Get all payrolls for a tenant
     *
	 * @param integer $tenant_id     
	 *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	 
	public function getPayrolls($tenant_id,Request $request){
		try{
			$payrolls = $request->has('paginate') ? CompanyPayroll::all()->paginate($request->paginate) : CompanyPayroll::all();
					
			if(sizeof($payrolls)){
				return response()->json($payrolls,200);
			}else{
				
				$message = 'no payrolls found for tenant id : $tenant_id';
				
				return response()->json(['message' => $message],401);
			}
		}catch(Exception $e){
			
		}
	
	}
	
	/**
     * Get a specific payroll for a tenant
     *
	 * @param integer $tenant_id        
	 *
	 * @param integer $payroll_id     
	 *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	 
	public function getPayroll($tenant_id,$payroll_id){
		try{
			//do something with tenant_id ? perhaps
			$payroll = Payroll::with('user')->where([
						['company_payroll_id', '=', $payroll_id],
					])->get(['uuid','amount','user_id','complete']);
									
			if(sizeof($payroll)){
				return response()->json($payroll,200);
			}else{
				
				$message = 'no payroll id: '.$payroll_id.' found for tenant id : '.$tenant_id;
				
				return response()->json(['message' => $message],401);
			}
		}catch(Exception $e){
			
		}
	
	}
	
	/**
     * Batch Process a specific payroll for a tenant
     *
	 * @param integer $tenant_id        
	 *
	 * @param integer $payroll_id     
	 *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	 
	public function batchProcessPayroll($tenant_id,$payroll_id){
		try{
			
			dispatch(new ProcessPayroll($payroll_id));
			
		}catch(Exception $e){
			
		}
		
	}
	
	/**
     * Verify Bank Account
     *
	 * @param integer $accountNumber        
	 *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	 
	public function verifyBank($accountNumber){
		
	}
	
}

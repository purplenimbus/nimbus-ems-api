<?php

namespace App\Http\Controllers;

use App\User as User;
use App\Payroll as Payroll;
use App\CompanyPayroll as CompanyPayroll;

use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
		
		$payrolls = $request->has('paginate') ? CompanyPayroll::all()->paginate($request->paginate) : CompanyPayroll::all();
				
		if(sizeof($payrolls)){
			return response()->json($payrolls,200);
		}else{
			
			$message = 'no payrolls found for tenant id : $tenant_id';
			
			return response()->json(['message' => $message],401);
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
		//do something with tenant_id ? perhaps
		$payroll = Payroll::with('user')->where([
					['company_payroll_id', '=', $payroll_id],
				])->get(['id','amount','user_id']);
								
		if(sizeof($payroll)){
			return response()->json($payroll,200);
		}else{
			
			$message = 'no payroll id: '.$payroll_id.' found for tenant id : '.$tenant_id;
			
			return response()->json(['message' => $message],401);
		}
	
	}
}

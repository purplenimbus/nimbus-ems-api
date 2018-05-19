<?php

namespace App\Http\Controllers;

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
     * Get all payroll for a tenant
     *
	 * @param integer $tenant_id     
	 *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	 
	public function getPayroll($tenant_id,Request $request){
				
	}
}

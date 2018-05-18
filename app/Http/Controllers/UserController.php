<?php

namespace App\Http\Controllers;
use App\Employee as Employee;
use App\Task as Task;
use App\User as User;

class TenantController extends Controller
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
	
	public function employee($user_id){
		
		$employees = Employee::where('user_id',$user_id)->paginate(10);
				
		if(sizeof($employees)){
			return response()->json(['data' => $employees],200);
		}else{
			
			$message = 'no employees found';
			
			return response()->json(['message' => $message],401);
		}
	}
	
	public function listEmployeeTasks($user_id,$employee_id){
		
		$tasks = Tasks::all()->where('user_id',$user_id)->paginate(10);
						
		if(sizeof($tasks)){
			return response()->json(['data' => $tasks],200);
		}else{
			
			$message = 'no tasks found for employee id : '.$employee_id;
			
			return response()->json(['message' => $message],401);
		}
	}
	
	public function listCompanyTasks($user_id){
		
		$tasks = Task::where('user_id',$user_id)->paginate(10);
				
		if(sizeof($tasks)){
			return response()->json(['data' => $tasks],200);
		}else{
			
			$message = 'no tasks found for employee id : '.$employee_id;
			
			return response()->json(['message' => $message],401);
		}
	}
	
	public function saveTasks(Request $request){
		
	}

    //
}

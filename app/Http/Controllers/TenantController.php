<?php
  
namespace App\Http\Controllers;
use App\Task as Task;
use App\User as User;
use App\Tenant as Tenant;
use App\Activity as Activity;

use Illuminate\Http\Request;


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
	/**
     * Get all tenants
     *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	public function getTenants(Request $request){
				
		$tenants = $request->has('paginate') ? Tenant::all()->paginate($request->paginate) : Tenant::all();
				
		if(sizeof($tenants)){
			return response()->json($tenants,200);
		}else{
			
			$message = 'no tenants found for tenant id : $tenant_id';
			
			return response()->json(['message' => $message],401);
		}
	}
	
	/**
     * Get a tenant
     *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	public function getTenant($tenant_id,Request $request){
				
		$tenant = Tenant::where([
					['id', '=', $tenant_id],
				])->get();
								
		if(sizeof($tenant)){
			return response()->json($tenant,200);
		}else{
			
			$message = 'no tenant found for id : '.$tenant_id;
			
			return response()->json(['message' => $message],401);
		}
		
	}

	/**
     * Get all users for a tenant
	 *
	 * @param integer $tenant_id
     *
	 * @param Illuminate\Http\Request $request
	 *
     * @return Illuminate\Http\Response
     */
	public function getUsers($tenant_id , Request $request){
				
		$users = $request->has('paginate') ? User::where('tenant_id',$tenant_id)->paginate($request->paginate) : User::where('tenant_id',$tenant_id)->get();
				
		if(sizeof($users)){
			return response()->json($users,200);
		}else{
			
			$message = 'no users found for tenant id : $tenant_id';
			
			return response()->json(['message' => $message],401);
		}
	}
	
	/**
     * Get a user from a tenant
	 *
	 * @param integer $tenant_id
     *
	 * @param integer $user_id
	 *
     * @return Illuminate\Http\Response
     */	
	public function getUser($tenant_id,$user_id){
		
		$user = User::where([
					['tenant_id', '=', $tenant_id],
					['id', '=', $user_id],
				])->get();
								
		if(sizeof($user)){
			return response()->json($user,200);
		}else{
			
			$message = 'no user id: '.$user_id.' found for tenant id : '.$tenant_id;
			
			return response()->json(['message' => $message],401);
		}
	}
	
	/**
     * Save user data
	 *
	 * @param integer $tenant_id
     *
	 * @param integer $user_id
	 *
     * @return Illuminate\Http\Response
     */	
	public function updateUser(Request $request){
		$this->validate($request, [
			'id' => 'required',
			'fname' => 'required',
			'lname' => 'required',
			//'email' => 'required|email|unique:users'
		]);
		
		$user = User::find($request->id);
		
		$user->fill($request->all());
		
		$user->save();
		
		return response()->json($user,200);
	}
	
	/**
     * Save tenant data
	 *
	 * @param integer $tenant_id
     *
     * @return Illuminate\Http\Request
     */	
	public function updateTenant(Request $request){
		$this->validate($request, [
			'id' => 'required',
			'name' => 'required',
		]);
		
		$tenant = Tenant::find($request->id);
		
		$tenant->fill($request->all());
		
		$tenant->save();
		
		return response()->json($tenant,200);
	}
	
	/*
	public function userTasks($tenant_id,$user_id,Request $request){
		$query = [
					['tenant_id', '=', $tenant_id],
					['user_id', '=', $user_id],
				];
		
		$tasks = $request->has('paginate') ? Task::where($query)->paginate($request->paginate) : Task::where($query)->get();
				
		if(sizeof($tasks)){
			return response()->json($tasks,200);
		}else{
			
			$message = 'no tasks found for user id : $user_id';
			
			return response()->json(['message' => $message],401);
		}
	}
	
	public function activities($tenant_id,Request $request){
		$query = [
					['tenant_id', '=', $tenant_id]
				];
		
		if($request->has('user_id')){
			array_push($query,['user_id', '=', $request->user_id]);
		}
		
		if($request->has('type')){
			array_push($query,['meta->action->type', $request->type]);
		}
				
		$activities = $request->has('paginate') ? Activity::with('user')->where($query)->paginate($request->paginate) : Activity::with('user')->where($query)->get();
						
		if(sizeof($activities)){
			return response()->json($activities,200);
		}else{
			
			$message = 'no activities found for tenant id : '.$tenant_id;
			
			return response()->json(['message' => $message],401);
		}
	}
    */
}

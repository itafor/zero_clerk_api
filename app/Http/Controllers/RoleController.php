<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
    	$roles = Role::all();
    	return response()->json(['roles'=>$roles]);
    }

     public function store(Request $request){
    	$roleData = $request->validate([
    		'name'=>'required'
    	]);

    	$get_role = Role::where('name',$request->name)->first();

       if($get_role){
    	return response()->json(['error'=>'Role already exist']);
    		}
    		
    	$role = Role::create($roleData);
    	return response()->json(['message'=>'Role created successfully','role'=>$role]);
    }

     public function update($roleId, Request $request){
    	$roleData = $request->validate([
    		'name'=>'required'
    	]);

    	$role = Role::where('id',$roleId)->first();

    	if($role){

    		$role->name = $request->name;
    		$role->save();

    	return response()->json(['message'=>'Role updated successfully']);
    	}
    }

    public function destroyRole($roleId){
    	$role = Role::find($roleId);
    	if($role){
    		$role->delete();
    	return response()->json(['message'=>'Role deleted successfully']);
    	}
    	return response()->json(['error'=>'Attempt to delete role failed']);
    }
}

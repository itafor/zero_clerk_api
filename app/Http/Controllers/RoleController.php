<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

 /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api','parentUser']);
    }

    public function storeRole(Request $request){
        $role = Role::where('name', $request->name)->first();
        if($role){
            return response()->json(['error'=>'Role already exist']);
        }

    	$role = Role::create(['name' => $request->name]);
    	return response()->json(['message'=>'Role created successfully']);
    }

     public function storePermission(Request $request){
        $permission = Permission::where('name', $request->name)->first();
        if($permission){
            return response()->json(['error'=>'permission already exist']);
        }

       $permission = Permission::create(['name' => $request->name]);
        return response()->json(['message'=>'permission created successfully']);
    }

    public function assignPermissionToRole(Request $request){

        $validatedData = $request->validate([
            'permission_name'=>'required',
            'role_name'=>'required',
        ]);

        $permission = Permission::where('name', $request->permission_name)->first();

        $role = Role::findByName($request->role_name);
        if($permission !='' && $role !=''){
     $assignPermission =  $role->givePermissionTo($request->permission_name);
     if($assignPermission){
        return response()->json(['message'=>'permission successfully assigned to selected role']);
     }
        return response()->json(['error'=>'An attempt to assign permission to role failed']);

    }else{
        return response()->json(['error'=>'Role or permission does not exist']);
    }
}

public function removePermissionFromRole(Request $request){

        $validatedData = $request->validate([
            'permission_name'=>'required',
            'role_name'=>'required',
        ]);

        $permission = Permission::where('name', $request->permission_name)->first();

        $role = Role::findByName($request->role_name);
        if($permission !='' && $role !=''){
         $permission_revoke = $role->revokePermissionTo($permission);
     if($permission_revoke){
        return response()->json(['message'=>'permission successfully revoke from the selected role']);
     }
        return response()->json(['error'=>'An attempt to revoke permission from the selected role failed']);

    }else{
        return response()->json(['error'=>'Role or permission does not exist']);
    }
}

    public function assignRoleToUser(Request $request){

        $validatedData = $request->validate([
            'user_id'=>'required',
            'roles'=>'required',
        ]);

        $user = User::where([
            ['id',$request->user_id],
            ['parent_id','!=',null]
        ])->first();

        if($user){
       $assignedRole = $user->assignRole($request->roles);
     if($assignedRole){
        return response()->json(['message'=>'Role successfully assigned to selected user']);
     }
        return response()->json(['error'=>'An attempt to assign role to the selected user failed']);

    }else{
        return response()->json(['error'=>'User does not exist']);
    }
}
   
}

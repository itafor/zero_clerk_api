<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function listPermissions(Request $request){
        $permissions = Permission::all();

        if(count($permissions) >= 1){
        return response()->json(['permissions'=>$permissions]);
        }
        return response(['error'=>'permission not found!!'],401);
    }


//working on this
    public function list_user_permissions(Request $request,$user_id){
        $user = User::where('id',$user_id)->first();
        if($user){

        $permissions = DB::table('model_has_permissions')->where('model_id',$user->id)->get();

        return response()->json(['permissions'=>$permissions]);
        }
    }

     public function listRoles(Request $request){
        $roles = Role::all();

        if(count($roles) >= 1){
        return response()->json(['roles'=>$roles]);
        }
        return response(['error'=>'role not found!!'],401);
    }

    public function assignPermissionToRole(Request $request){

        $validatedData = $request->validate([
            'permission_name'=>'required',
            'role_name'=>'required',
        ]);

        $permission = Permission::where('name', $request->permission_name)->first();

        $role = Role::findByName($request->role_name);

     $roleHasPermission =  $role->hasPermissionTo($request->permission_name);

        if($roleHasPermission){
        return response()->json(['error'=>'Permission already exist']);
        }

        if($permission !='' && $role !=''){
     $assignPermission =  $role->givePermissionTo($request->permission_name);
     if($assignPermission){
        return response()->json(['message'=>'Permission successfully assigned to selected role']);
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

        $data=$request->all();

        $user = User::where([
            ['id',$request->user_id],
            ['parent_id','!=',null]
        ])->first();
       // ->orwhere('id',authUser()->id)->first();

     // $userHasRole = $user->hasAnyRole($data['roles']);

     // if($userHasRole){
     //   return response()->json(['error'=>'Selected role already exist']);
     // }
    
       if($user){
            foreach ($data['roles'] as $key => $role) {
         $assignedRole = $user->assignRole($role['name']);
            }

     if($assignedRole){
        return response()->json(['message'=>'Role successfully assigned to selected user']);
     }
        return response()->json(['error'=>'An attempt to assign role to the selected user failed']);

    }else{
        return response()->json(['error'=>'User does not exist']);
    }
}

    public function revokeUserRole(Request $request){

        $validatedData = $request->validate([
            'user_id'=>'required',
            'role'=>'required',
        ]);

        $user = User::where([
            ['id',$request->user_id],
            ['parent_id','!=',null]
        ])->first();

        if($user){
       $revokeRole =  $user->removeRole($request->role);

     if($revokeRole){
        return response()->json(['message'=>'Role successfully revoked']);
     }
        return response()->json(['error'=>'An attempt to revoke failed']);

    }else{
        return response()->json(['error'=>'User does not exist']);
    }
}
   

    public function giveDirectPermissionToUser(Request $request){

        $validatedData = $request->validate([
            'user_id'=>'required',
            'permissions'=>'required',
        ]);

        $data=$request->all();

        $user = User::where([
            ['id',$request->user_id],
            ['parent_id','!=',null]
        ])->first();

     // $userHasRole = $user->hasAnyRole($data['roles']);

     // if($userHasRole){
     //   return response()->json(['error'=>'Selected role already exist']);
     // }
    
       if($user){
          $assignPermission = $user->givePermissionTo($data['permissions']);

     if($assignPermission){
        return response()->json(['message'=>'Permission(s) successfully assigned to selected user']);
     }
        return response()->json(['error'=>'An attempt to assign selected permissions failed']);

    }else{
        return response()->json(['error'=>'User does not exist']);
    }
}

public function removeUserDirectPermission(Request $request){

        $validatedData = $request->validate([
            'permission_name'=>'required',
            'user_id'=>'required',
        ]);


         $data=$request->all();

        $user = User::where([
            ['id',$request->user_id],
            ['parent_id','!=',null]
        ])->first();

        if($user){
         $permission_revoke = $user->revokePermissionTo($request->permission_name);
     if($permission_revoke){
        return response()->json(['message'=>'permission successfully revoke from the selected user']);
     }
        return response()->json(['error'=>'An attempt to revoke permission from the selected user failed']);

    }else{
        return response()->json(['error'=>'User does not exist']);
    }
}
}

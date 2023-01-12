<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use App\Http\Resources\RoleResource;

class RoleController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()  
    {
        $this->middleware(['auth:api'], ['except' => []]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::all();
        return RoleResource::collection($role);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = new Role;
        $role->role = $request->role;
        $role->save();

        return "created";
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAccountRequest  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role, $id)
    {
        $role = Role::find($id);
        $role->role = $request->role;
        $role->save();

        return 'Updated';
    }

    // trashed
    public function trashed() {
        $role = Role::onlyTrashed()->get();
        return RoleResource::collection($role);
    }

    // delete
    public function destroy($id) {
        $role = Role::withTrashed()
        ->where('id', $id);
        $role->delete();
        return 'delete';
    }

    // restore
    public function restore($id) {
        $role = Role::onlyTrashed()
        ->where('id', $id);
        $role->restore();
        return 'restore';
    }

    // forced
    public function forced($id) {
        $role = Role::onlyTrashed()
        ->where('id', $id);
        $role->forceDelete();
        return 'forced';
    }
}

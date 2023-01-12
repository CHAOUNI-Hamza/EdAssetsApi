<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller\authorize;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()  
    {
        $this->middleware(['auth:api'], ['except' => ['login']]);
    }

    public function index() {
        $users = User::all();
        return UserResource::collection($users);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Register New User
     */

    public function register(Request $request)
    {
        $this->authorize("user.register");

        $user = new User;
        $user->username = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->birthday = $request->birthday;
        $user->role = $request->role;
        $user->state = $request->state;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return "created";
    }

    public function update(Request $request, $id) {

        $user = User::find($id);
        $user->username = $request->username;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->birthday = $request->birthday;
        $user->role = $request->role;
        $user->state = $request->state;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return 'Updated';
    }

    // trashed
    public function trashed() {
        $users = User::onlyTrashed()->get();
        return UserResource::collection($users);
    }

    // delete
    public function destroy($id) {
        $user = User::withTrashed()
        ->where('id', $id);
        $user->delete();
        return 'delete';
    }

    // restore
    public function restore($id) {
        $user = User::onlyTrashed()
        ->where('id', $id);
        $user->restore();
        return 'restore';
    }

    // forced
    public function forced($id) {
        $user = User::onlyTrashed()
        ->where('id', $id);
        $user->forceDelete();
        return 'forced';
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
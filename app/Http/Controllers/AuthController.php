<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller\authorize;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
//use App\Http\Controllers\Controller\authorize;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()  
    {
        //$this->middleware(['auth:api'], ['except' => ['login','store']]);
    } 

    public function index() {
        $users = User::with('roles')->paginate(70);
        return UserResource::collection($users);
    }

    /**
     * Shiw One User
     */

    public function show(User $id) {
        /*$user = DB::table('users')->find($id);
        //return $user;*/
        return new UserResource($id);
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

    public function store(Request $request)
    {
        //$this->authorize("user.register");

        $user = new User;
        $user->username = $request->username;
        $user->nickname = $request->nickname;
        $user->phone = $request->phone;
        $user->cin = $request->cin;
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

    // Forgot Password
    public function forgotpassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status;
    
        if ($status == Password::RESET_LINK_SENT) {
            return [
                'status' => __($status)
            ];
        };
    
        throw ValidationException::withMessages([
            'email' => [trans($status)]
        ]);
    }

    // reset password
    public function resetpassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', RulesPassword::defaults()],
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
    
                $user->tokens()->delete();
    
                event(new PasswordReset($user));
            }
        );
    
        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully'
            ]);
        }
    
        return response([
            'message'=> __($status)
        ], 500);
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
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        return User::where('role', 'user')->with('profile')->get();
    }
    public function login(Request $request){
        $data = $request->only('email', 'password');
        
        if(Auth::attempt($data)){
            $user = Auth::user();
            if($user->active){
                $token = $user->createToken('authToken')->plainTextToken;

                $u = User::with('profile')->find($user->id);
                $savedEvents = User::find($user->id)->savedevents()->with('event.brand')->get();
                return response()->json(['success'=>true, 'token' => $token, 'user' => $u, 'savedEvents' => $savedEvents], 200);
            }else{
                return response()->json(['success'=>false]);
            }
        }
        return response()->json(['message' => 'Credenciales invalidas'], 401);
    }

    public function logout(Request $request){
        // return 'logout';
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $data = $request->only('name', 'last_name', 'birthday', 'email', 'password');
        $user = User::where('email', $data['email'])->first();
        if($user){
            return response()->json(['exists' => true]);
        }else{
            $profile = Profile::create([]);
            $data['birthday'] = Date($data['birthday']);
            $data['password'] = bcrypt($data['password']);
            $data['role'] = 'user';
            $data['active'] = 1;
            $data['profile_id'] = $profile->id;
            User::create($data);
            return response()->json(['exists' => false], 200);
        }
    }

    public function block(User $user) {
        $user->update([
            'active' => !$user->active // Alterna entre 0 y 1
        ]);
    
        $users = User::where('role', 'user')->with('profile')->get();
    
        return response()->json(['success' => true, 'users' => $users], 200);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, Request $request)
    {
        $user->update([
            'name' => $request->name
        ]);
        $u = User::find($user->id)->with('profile')->first();
        return response()->json(['success'=>true, 'user'=>$u], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

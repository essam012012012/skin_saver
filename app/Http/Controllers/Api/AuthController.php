<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{//1 validate 2create 3 token 4 response
   public function register(Request $request){
    $request -> validate([//بتخليه يبعت الداتا بالشكل الي انا عايزوا
        'name'=>'required|string|max:255',
        'email'=>'required|email|string|max:255|unique:users,email',
        'phone'=>'required|digits:11|max:255|unique:users,phone',
        'password'=>'required|min:6|confirmed',//بتماتش الاتنين ببعض
        'password_confirmation'=>'required|min:6'
    ]);
    $user = User::create([
        'name'=>$request -> name ,
        'email'=>$request -> email ,
        'phone'=>$request -> phone ,
        'password'=>Hash::make($request -> password),
    ]);
    $token = $user -> createToken('API TOKEN')->plainTextToken; //هيخليها تعرف تتقري

    return response()->json(['token'=>$token],200);
   }

   public function login(Request $request){
    $request -> validate([
        'email'=>'required|email|string|max:255|exists:users,email',
        'password'=>'required|min:6'
    ]);
    //بيروح يدور علي اليوسر بالاميل والباس
    if (!Auth::attempt($request->only('email', 'password'))) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    //بيروح يدور علي بيانات اليوسر ويتأكد انها موجوده ويرجع اول ريكورد
    $user = User::where('email',$request ->email)->firstOrFail();
    $token = $user -> createToken('API TOKEN')->plainTextToken;//هيخليها تعرف تتقري
    return response()->json(['token'=>$token],200);
    }

    public function logout(){
        request()->user()->tokens()->delete();
        return response()->json(['message'=>'logged out'],200);
    }
}



<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(){
        $messages = ContactUs::select('contact_us.*')->orderBy('id')->paginate(25);
        return response()->json(['data'=> $messages],200);
    }

    public function store(Request $request){
        $request -> validate([
            'name'=> 'required|string|max:255',
            'email'=>'required|email|exists:users,email',
            'message'=>'required|string|max:255'
        ]);
        $contactus = ContactUs::create([
            'name'=>$request -> name ,
            'email'=>$request -> email ,
            'message'=>$request -> message ,
        ]);
        return response()->json(['data'=> 'done'],200);
    }

}

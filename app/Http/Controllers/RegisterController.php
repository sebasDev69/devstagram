<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class RegisterController extends Controller
{
    public function index(){
        return view('auth.register');
    }

    public function store(Request $request){
        //Validación
        $this->validate($request,[
            'name' =>'required|min:5|max:30',
            'username' =>'required|unique:users|max:30|min:3',
            'email'=>'required|unique:users|email|max:60',
            'password'=>'required|confirmed|min:6'
        ]);

        //Modificar el request
        $request->request->add(['username'=>Str::slug($request->username)]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,//Poner el username en minusculas
            'email' => $request->email,
            'password' => $request->password,

        ]);

        //Autenticar al usuario
        auth()->attempt([
            'email' =>$request->email,
            'password'=>$request->password,
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }
}

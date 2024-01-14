<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
//    inicializa el middelwaresolo para los que estan autenticados
    public function __construct(){
        $this->middleware('auth');
    }



    //manda a llamar automaticamente sin necesidad de poner una referencai de metodo
    public function __invoke(){

        //Obtener a quienes seguimos // pluck para obtener ciertos campos
        $ids= auth()->user()->followings->pluck('id')->toArray();
        //latest = ordena automaticamente por fecha
        $posts = Post::whereIn('user_id', $ids)->latest()->paginate(20);
        return view('home',['posts'=>$posts]);
    }
}

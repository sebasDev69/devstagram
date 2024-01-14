<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {

        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [ //.auth()->user()->id para editar el username verificando el id
            'username' => ['required', 'unique:users,username,' . auth()->user()->id, 'max:30', 'min:3', 'not_in:twitter,editar-perfil,'], //not_in: lista negra de nombres
        ]);

        if ($request->imagen) {
            $imagen = $request->file('imagen');
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            $imagenServidor = Image::make($imagen);
            $imagenServidor->fit(1000, 1000);
            $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
            $imagenServidor->save($imagenPath);
        }
        //Guardar cambios

        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen= $nombreImagen?? auth()->user()->imagen ?? null;
        $usuario->save();

        return redirect()->route('posts.index', $usuario->username);

    }
}

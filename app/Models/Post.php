<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'user_id'
    ];

    #relación de Uno a muchos //One to many
    public function user()
    {
        //select = para especificar que campos queremos que obtenga de la tabla con el fin de no usar muchas validaciones en el controlador
        return $this->belongsTo(User::class)->select(['name','username']);
    }

    public function comentarios(){

        return $this->hasMany(Comentario::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }

    public function checkLike(User $user){
        return $this->likes->contains('user_id', $user->id);
    }

}

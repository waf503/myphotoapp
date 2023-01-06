<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function config () {
        return view('user.config');
    }

    public function index($search = null) {
        if(!empty($search)){
            $users = User::orderBy('id','desc')
                            ->where('nick', 'LIKE', '%'.$search.'%')
                            ->orWhere('name','LIKE','%'.$search.'%')
                            ->orWhere('surname','LIKE','%'.$search.'%')
                            ->orwhereRaw("concat(name, ' ', surname) like '%" .$search. "%' ")
                            ->paginate(1);
        }
        else{
            $users = User::orderBy('id', 'desc')->paginate(5);
        }        

        return view('user.index', [
            'users' => $users
        ]);
    }

    public function update (Request $request){
        
        //var_dump(Storage::disk());
        //die();

        //Obtener el usuario identificado
        $user = \Auth::user();
        $id = $user->id;

        //validando del formulario
        $validate = $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:users,nick,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'description' => 'required|string'
        ]);

        //Obtener datos del form
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');
        $description = $request->input('description');

        //Asignar nuevos valores al obj de usuario
        $user->name = $name;
        $user->surname = $surname;
        $user->nick = $nick;
        $user->email = $email;
        $user->description = $description;

        //subir la imagen
        
        $image_path = $request->file('image_path');

        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            
            Storage::disk('users')->put($image_path_name, File::get($image_path));

            $user->image = $image_path_name;
        }

        //Ejecutar consulta y cambios en la base de datos
        $user->update();

        return redirect()->route('config')->with(['message'=>'Usuario actualizado correctamente']);
    }

    public function getImage($filename) {
        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);
    }

    public function profile($id) {
        $user = User::find($id);

        return view('user.profile', [
            'user' => $user
        ]);
    }



    
}

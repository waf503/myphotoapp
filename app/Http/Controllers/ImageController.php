<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class ImageController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('image.create');
    }

    public function save (Request $request) {
        //validacion
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'required|mimes:jpg,jpeg,png,gif'
        ]);
        //Recogiendo los datos
        $user = \Auth::user();
        $description = $request->input('description');
        $image_path = $request->file('image_path');
        

        
        $image = new Image();
        $image->user_id = $user->id;
        $image->description = $description;

        //subir fichero
        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));

            $image->image_path = $image_path_name;
        }

        $image->save();

        return redirect()->route('home')->with([
            'message'=>'La foto ha sido subida correctamente'
        ]);


    }

    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    public function detail($id) {
        $image = Image::find($id);

        return view('image.detail',[
            'image'=>$image
        ]);
    }

    public function delete($id) {
        $image = Image::find($id);
        $user = \Auth::user();
        $comments = Comment::where('image_id', $id)->get();
        $likes = Like::where('image_id',$id)->get();

        if($user && $image && $image->user->id == $user->id){
            if($comments && count($comments) >= 1){
                foreach($comments as $comment){
                    $comment->delete();
                }
            }

            if($likes && count($likes) >= 1){
                foreach($likes as $like){
                    $like->delete();
                }
            }

            //eliminar imagen del storage
            Storage::disk('images')->delete($image->image_path);
            //Eliminar la imagen
            $image->delete();

            $message = array('message'=>'La imagen se ha borrado correctamente.');
        }else{
            $message = array('message'=>'La imagen no se borro correctamente.');
        }

        return redirect()->route('home')->with($message);
    }

    public function update($id){
        $user = \Auth::user();

        $image = Image::find($id);

        if($user && $image && $image->user->id == $user->id){
            return view('image.edit', [
                'image'=>$image
            ]);
        }else{
            return redirect()->route('home');
        }
    }

    public function actualizar(Request $request){
        $validated = $request->validate([
            'image_path' => 'image',
            'image_id' => 'required',
            'description' => 'required|string',
        ]);

        $image_id = $request->input('image_id');
        $description = $request->input('description');
        $image_path = $request->file('image_path');
        

        $image = Image::find($image_id);
        $old_image_path = $image->image_path;
        $image->description = $description;
        //subir fichero
        if($image_path){
            $image_path_name = time().$image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));

            $image->image_path = $image_path_name;
            //eliminar imagen del storage
            Storage::disk('images')->delete($old_image_path);
        }
        //
        $image->update();
        

        return redirect()->route('image.detail',['id'=> $image_id])
                            ->with(['message' => 'Imagen actualizada con exito.']);
    }
}

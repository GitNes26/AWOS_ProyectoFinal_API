<?php

namespace App\Http\Controllers\controladores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Validation\ValidationException;
use Illumainate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class users extends Controller
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function registro(Request $request){
    $request->validate([
        'name'=>'required',
        'email'=>'required|email',
        'password'=>'required'
    ]);
    $usuario= new User;   
    $usuario->name =$request->name; 
    $usuario->foto_perfil ="";
    $usuario->foto_de_perro ="";
    $usuario->email = $request->email;
    $usuario->email_verified_at =now();
    $usuario->password = Hash::make($request->password);
    $usuario->perro =$request->perro;
    $usuario->permiso ="normal";  
    $usuario->save();
    return response()->json(["data"=>$usuario],200);

}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function actualizar(Request $request){
    if($request->user()->tokenCan('autenticado')){
          
        $users=DB::table('users')
        ->select('users.id')
        ->Where('users.email', $request->user()->email)->get();
        $lalo= $request->user()->email;
        foreach( $users as $value){
$id=$value->id;
        }
 $usuario=User::find($id);
    $usuario->name =$request->name; 
    $usuario->email_verified_at =now();
    $usuario->perro =$request->perro;
    $usuario->save();
    return response()->json([$usuario,],200);

    }
}
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function verperfil(Request $request)
    {
        if($request->user()->tokenCan('autenticado')){
          
            $users=DB::table('users')
            ->select('users.id')
            ->Where('users.email', $request->user()->email)->get();
            $lalo= $request->user()->email;
            foreach( $users as $value){
                $id=$value->id;
            }
            $usuario=User::find($id);
            return response()->json(['data'=>$usuario,],200);
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function solicitardatos(){

        $response = Http::get('http://192.168.0.10:80/api/vista');
       

        
              return response()->json($response->json(),$response->status());

    }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    public function logOut(Request $request){
        return response()->json(["Tokens eliminados"=>$request->user()->tokens()->delete()],200);
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function logIn(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $usuario = User::where('email',$request->email)->first();

        if(!$usuario || !Hash::check($request->password, $usuario->password)){
            throw ValidationException::withMessages([
                'email|password' => ['Correo o Contraseña Incorrecta']
            ]);
        }

        $token = $usuario->createToken($request->email,['autenticado'])->plainTextToken;
            return response()->json(['token'=>$token],201);
    }
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
    public function guardarfotodepersona(Request $request){
        if($request->user()->tokenCan('autenticado')){
          
            $users=DB::table('users')
            ->select('users.id')
            ->Where('users.email', $request->user()->email)->get();
            $lalo= $request->user()->email;
            foreach( $users as $value){
$id=$value->id;
            }
            if($request->hasFile('file')){
                $imagenenbasededatos=User::find($id);
                $path=storage::disk('public')->put('imagenes', $request->file);
    $file = $request->file('file');
                //obtenemos el nombre del archivo
                $nombre =  time()."_".$file->getClientOriginalName();
                
                $imagen='storage/app/documentacion/'.$path;
                //indicamos que queremos guardar un nuevo archivo en el disco local
    
                $imagenenbasededatos = User::find($id);
                $imagenenbasededatos->foto_perfil = $imagen;
                $imagenenbasededatos->save();
                return response()->json(['su id de la documentacion
                para la persona es el campo id',$imagenenbasededatos],200);
            }
            }
        }
        
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function guardarfotodeperro(Request $request){
            if($request->user()->tokenCan('autenticado')){
                $users=DB::table('users')
                ->select('users.id')
                ->Where('users.email', $request->user()->email)->get();
                $lalo= $request->user()->email;
                foreach( $users as $value){
    $id=$value->id;
                }
                if($request->hasFile('file')){
                    $imagenenbasededatos=User::find($id);
                    $path=storage::disk('public')->put('imagenes', $request->file);
        $file = $request->file('file');
                    //obtenemos el nombre del archivo
                    $nombre =  time()."_".$file->getClientOriginalName();
                    
                    $imagen='storage/app/documentacion/'.$path;
                    //indicamos que queremos guardar un nuevo archivo en el disco local
        
                    $imagenenbasededatos = User::find($id);
                    $imagenenbasededatos->foto_de_perro = $imagen;
                    $imagenenbasededatos->save();
                    return response()->json(['su id de la documentacion
                    para la persona es el campo id',$imagenenbasededatos],200);
                }
             
        
                }
            }
}


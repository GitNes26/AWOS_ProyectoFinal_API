<?php

namespace App\Http\Controllers\controladores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\modelos\registro_sensores;
use registro_sensores as GlobalRegistro_sensores;
use Illuminate\Support\Facades\DB;
use \Mailjet\Resources;

class registro_sensoress extends Controller
{
    public function solicitardatos(Request $request){
        if($request->user()->tokenCan('autenticado')){
          
                $users=DB::table('users')
                ->select('users.id')
                ->Where('users.email', $request->user()->email)->get();
                $lalo= $request->user()->email;
                foreach( $users as $value){
    $id=$value->id;
                }
        $response = Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/cantidad-de-croquetas');
        if($response->json()){
          $sUltrasonico= Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/cantidad-de-croquetas')['last_value'];
          $sTemperatura= Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/temperatura')['last_value'];
          $sPir= Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/el-perro-esta-comiendo')['last_value'];
          $sFotoresistencia= Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/estado-del-plato')['last_value'];
          $sHumedad= Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/humedad')['last_value'];
          $boton= Http::get('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/controll-buttons')['last_value'];


          $datosSensores=new registro_sensores;
          $datosSensores->humedad=$sHumedad;
          $datosSensores->temperatura=$sTemperatura;
          $datosSensores->ultrasonico=$sUltrasonico;
          // $datosSensores->humedad=settype($sHumedad,double);
          // $datosSensores->temperatura=settype($sTemperatura,double);
          // $datosSensores->ultrasonico=settype($sUltrasonico,double);

          $datosSensores->fotoresistencia=$sFotoresistencia;

          $datosSensores->pir=$sPir;
          $datosSensores->boton=$boton;

          $datosSensores->user_id=$id;

  //https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/humedad
          $datosSensores->created_at= now();
          $datosSensores->updated_at= now();

          $datosSensores->save();
        }

        if($sFotoresistencia<100)
        {
          $correo= $request->user()->email;
          $this->vacio($correo);
        }   
        $datosSensores= registro_sensores::where('user_id', $id)->orderByDesc('id')->limit(1)->get();
        return response()->json(["sensores"=>$datosSensores],201);
    }

}
public function insertardatos(Request $request){
    if($request->user()->tokenCan('autenticado')){
        $response = Http::post('https://io.adafruit.com/api/v2/EduardoZarzosa/feeds/controll-buttons/data?X-AIO-Key=aio_LtNb74qdsGchiNpCAaYDP9Ue9sYd', [
            'value' => $request->miboton,

        ]);
        if($response->ok()){
          return response()->json(["compuerta"=>$response->json()]);
            // return response()->json($response->json(),$response->status());
        }
        return response()->json($response->json(),$response->status());

    }
}public function vacio($correo){
        $apikey =config('app.mjapikeypub');
        $apisecret=config('app.mjapikeypriv');
         $correoenvia=config('app.mjcorreo');
         $nombreenvia =config('app.mjquienloenvia');
        
        $mj = new \Mailjet\Client( $apikey,$apisecret,true,['version' => 'v3.1']);
          $body = [
            'Messages' => [
              [
                'From' => [
                  'Email' => $correoenvia,
                 'Name' => $nombreenvia
                ],
                'To' => [
                  [
                    'Email' => $correo,
                    'Name' => "Nuevo usuario"
                  ]
                ],
                'Subject' => "Greetings from Mailjet.",
                'TextPart' => "My first Mailjet email",
                'HTMLPart' => "
                </h2>El Tazon esta vacio</h2><br />",
                'CustomID' => "AppGettingStartedTest"
              ]
            ]
          ];
          $response = $mj->post(Resources::$Email, ['body' => $body]);
          if($response->success())
              return response()->json(["por favor verifique su correo y ingrese sus datos"
              =>$response->getData()],200);
              return response()->json(["mensaje"=>$response->getData()],500);
  }
}   

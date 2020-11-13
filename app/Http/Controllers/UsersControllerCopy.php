<?php   
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\UserStoreRequest;

use App\Http\Requests\WorkerStoreRequest;

use App\Http\Requests\UserEditRequest;

class UsersController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo generar una clave de acceso'], 500);
        }
        $user=JWTAuth::user();
        return response()->json(compact('token','user'));
    }

    public function adminEnter(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo generar una clave de acceso'], 500);
        }
        $user=JWTAuth::user();
        if(!$user->role=="admin"&&!$user->role=="worker") return response()->json(["error"=>'No tienes permisos'],403);
        return response()->json(compact('token','user'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['error'=>"Token expirado"], 401);
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], 403);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], 400);
        }
        return response()->json(compact('user'));
    }
    public function register(UserStoreRequest $request)
    {

        $validated = $request->validated();
        
        $data=$request->all();

        $data['password'] = Hash::make($request->get('password'));        

        $user = User::create($data);

        if(!$user) response()->json(["error"=>"Error en la base de datos"],500);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function registerPersonal(WorkerStoreRequest $request)
    {

        $validated = $request->validated();        

        $data=$request->all();

        $data['password'] = Hash::make($request->get('password'));        

        $user = User::create($data);

        if(!$user) response()->json(["error"=>"Error en la base de datos"],500);

        $user->role="worker";
        $user->area=$data["area"];

        if(!$user->save()) response()->json(["error"=>"Error en la base de datos"],500);        

        return response()->json(["message"=>"miembro del personal añadido"],201);
    }

    public function getPersonalAdded(){
        return DB::table('users')
        ->where("role", "worker")
        ->join('services', 'services.id','users.area')
        ->latest()
        ->limit(4)
        ->select("users.*", "services.name as area_name")
        ->get();
    }

    public function getByService($service){
        return User::where("role","=","worker")->where("area","=",$service)->get();
    }

    public function editData(UserEditRequest $request){
        $validated= $request->validated();

        $user= User::find(JWTAuth::user()->id);

        if(!$user) response()->json(["error"=>"Usuario no encontrado"],500);        

        $data=$request->all();

        if($request->hasFile("profile_picture")) {
            $file=$request->file("profile_picture");          
            $mime=$file->getMimeType();         
            $filename = $file->getClientOriginalName();         
            $file->move(public_path('uploads/images'), $filename);
            $data["profile_picture"]='uploads/images/'.$filename;
        }else{
            $data["profile_picture"]=null;
        }

        $user->update($data);

        if(!$user->save()) response()->json(["error"=>"Error en la base de datos"],500);        

        return response()->json(["message"=>"perfil actualizado exitosamente"],201);
    }
}
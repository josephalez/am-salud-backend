<?php   
namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\StripeClient;
use App\models\Relationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\WorkerEditRequest;
use App\Http\Requests\WorkerStoreRequest;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class UsersController extends Controller
{
    public function authenticate(Request $request)
    {

        $request->validate([
            'email'       => 'required_without:username|string|email',
            'username'    => 'required_without:email|string',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        if($request->has('username')){
            $credentials = request(['username', 'password']);
        }else{
            $credentials = request(['email', 'password']);
        }
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => __('auth.failed')], 401);
        }
        $user = $request->user();
        $user->load([
                "reservations"=>function($query){
                    $query->select(DB::raw('count(*) as count,user'))->where("servicio_id",2)->where('asociado',null)->groupBy('user','servicio_id');
                }
            ]);

        
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();


        $sql="UPDATE mis_paquetes SET status=1 WHERE vence<=CURDATE()";
        $pdo = DB::getPdo();
        $pdo->query($sql);

        


        return response()->json([
            'token' => $tokenResult->accessToken,
            'user'   => $user
        ]);
    }

    public function adminEnter(Request $request)
    {
        $request->validate([
            'email'       => 'required_without:username|string|email',
            'username'    => 'required_without:email|string',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        if($request->has('username')){
            $credentials = request(['username', 'password']);
        }else{
            $credentials = request(['email', 'password']);
        }
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => __('auth.failed')], 401);
        }
        $user = $request->user();
        if(!$user->role=="admin"&&!$user->role=="worker"&&!$user->role=='atencion'){
            return response()->json(["error"=>'No tienes permisos'],403);
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'token' => $tokenResult->accessToken,
            'user'   => $user
        ]);
    }

    public function getAuthenticatedUser()
    {
        $user=Auth::user();
        return response()->json(compact('user'));
    }

    public function register(UserStoreRequest $request)
    {

        $validated = $request->validated();
        
        $data=$request->all();

        $data['password'] = Hash::make($request->get('password'));
        
        $data['birthday'] = Carbon::parse($data['birthday'])->format('Y-m-d');
        
        $user = User::create($data);


        Stripe::setApiKey(config('services.stripe.secret'));
        $customer=Customer::create([
          'description' => 'Cliente para el usuiaro '.$user->email,
          'name'=>$user->name." ".$user->last_name,
          'email'=>$user->email
        ]);
        /*$data=[
            'stripe_id'=>$customer->id,
            'user_id'=>$user->id,
            'object'=>$customer->object
        ];
        $stripe=StripeClient::create($data);*/
        $user->showOrCreateClient();


        if(!$user) response()->json(["error"=>"Error en la base de datos"],500);

        $tokenResult = $user->createToken('Personal Access Token');
        $token=$tokenResult->accessToken;

        if($request->has("by_user")){
            $relationship= Relationship::create(["user_id"=>$data['by_user'],"user_related"=>$user->id]);
        }

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
        if($service==1){
            return User::has('authprices')->where("role","=","worker")->where("area","=",$service)->get();
        }
        return User::where("role","=","worker")->where("area","=",$service)->get();
    }

    public function editData(UserEditRequest $request){
        $validated= $request->validated();

        //$user= User::find(JWTAuth::user()->id);

        $user=Auth::user();

        if(!$user) response()->json(["error"=>"Usuario no encontrado"],500);        

        $data=$request->all();

        if($request->hasFile("profile_picture")) {
            $file=$request->file("profile_picture");          
            $mime=$file->getMimeType();         
            $filename = $file->getClientOriginalName();         
            $file->move(public_path('uploads/images'), $filename);
            $data["profile_picture"]='uploads/images/'.$filename;
        }

        $user->update($data);

        if(!$user->save()) response()->json(["error"=>"Error en la base de datos"],500);        

        return response()->json(["message"=>"perfil actualizado exitosamente"],201);
    }

    public function editWorker(WorkerEditRequest $request, $worker_id){
        $validated= $request->validated();

        $user=User::find($worker_id);

        if(!$user) response()->json(["error"=>"Usuario no encontrado"],404);        

        $data=$request->all();

        if($request->hasFile("profile_picture")) {
            $file=$request->file("profile_picture");          
            $mime=$file->getMimeType();         
            $filename = $file->getClientOriginalName();         
            $file->move(public_path('uploads/images'), $filename);
            $data["profile_picture"]='uploads/images/'.$filename;
        }

        if($user->update($data)) response()->json(["error"=>"Error en la base de datos"],500);        

        return response()->json(["message"=>"perfil actualizado exitosamente"],201);
    }
}
<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\ApiController;

use App\Http\Requests\Personal\PersonalStoreRequest;

use App\Models\Personal;

class PersonalController extends ApiController
{
    
    public function index(Request $request)
    {

        return $this->paginateAll(new Personal ,$request); 

    }

    /**
     * Display the specified resource.
     * 
     * @param  \App\Models\Worker  $worker
     * @return \Illuminate\Http\Response
     */

    public function store(PersonalStoreRequest $request){
        
        $validated = $request->validated();        

        $data=$request->all(); 

        if($request->hasFile("profile_picture")) {
            $file=$request->file("profile_picture");          
            $mime=$file->getMimeType();         
            $filename = $file->getClientOriginalName();         
            $file->move(public_path('uploads/images'), $filename);
            $data["profile_picture"]='uploads/images/'.$filename;
        }

        $personal = Personal::create($data);

        if(!$personal) response()->json(["error"=>"Error en la base de datos"],500);

        return response()->json(["message"=>"miembro del personal añadido a la sección 'Nosotros'"],201);
    }

    public function update(PersonalStoreRequest $request, $personal_id){
        $validated = $request->validated();        

        $data=$request->all(); 

        if($request->hasFile("profile_picture")) {
            $file=$request->file("profile_picture");          
            $mime=$file->getMimeType();         
            $filename = $file->getClientOriginalName();         
            $file->move(public_path('uploads/images'), $filename);
            $data["profile_picture"]='uploads/images/'.$filename;
        }

        $personal = Personal::find($personal_id);

        if(!$personal) response()->json(["error"=>"Error en la base de datos"],500);
        
        if(!$personal->update($data)) response()->json(["error"=>"Error en la base de datos"],500);

        return response()->json(["message"=>"miembro del personal actualizado"],201);
    }

    public function destroy($personal)
    {

        $personal= Personal::find($personal);

        if(!$personal) return $this->errorResponse('El Trabajador no existe',404);
        
        if(!$personal->delete()) $this->errorResponse('Error en la base de datos',500);
        
        return $this->showOne($personal,200);

    }
 

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Http\Requests\ServiceStoreRequest;

use App\Service;

class ServicesController extends Controller
{
    public function getAll(){
        return Service::all();
    }

    public function add(ServiceStoreRequest $request){

        $validated = $request->validated();

        $data=$request->all();

        $service=Service::create($data);

        if(!$service) return response()->json(["error"=>"Error en la base de datos"],500);

        return response()->json(['message'=>"Servicio añadido exitosamente"],201);
    }

    public function edit(ServiceStoreRequest $request,$id){

        $validated = $request->validated();

        $data= $request->all();

        $service= Service::find($id);

        if(!$service) return response()->json(["error"=>"No se encontró el servicio"],404);

        if(!$service->update($data)) return response()->json(["error"=>"Error en la base de datos"],500);

        return response()->json(["message"=>"Servico editado exitosamente"],200);

    }

    public function remove($id){

        $service= Service::find($id);

        if(!$service) return response()->json(["error"=>"No se encontró el servicio"],404);

        if(!$service->delete()) return response()->json(["error"=>"Error en la base de datos"],500);

        return response()->json(["message"=>"Servico removido exitosamente"],200);

    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Items;

use Illuminate\Http\Request;

class OphyraController extends Controller
{
    public function getItems(){

        $items = Items::all();

        return response()->json(['data' => $items], 200);
    }

    public function createItems(Request $request){

        $item = Items::create([
            'imagen' => $request->imagen,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
        ]);
        
        return response()->json(['message' => 'Creado con éxito'], 200);
    }

    public function deleteItems(Request $request){

        $item =Items::where('id',$request->id)->delete();

        if($item){
            return response()->json(['mesagge' => 'Eliminado con exito'], 200);
        }
    }

    public function updateItems(Request $request){


        $item = Items::where('id',$request->id)->first();

        $item->titulo = $request->titulo;
        $item->descripcion = $request->descripcion;
        $item->precio = $request->precio;
        $item->imagen = $request->imagen;

        $item->save();

        return response()->json(['mesagge' => 'Actualizado con éxito'], 200);
    }
}

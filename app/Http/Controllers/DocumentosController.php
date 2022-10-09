<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clientes;
use App\Models\matriculas;
use App\Models\medio_pagos;
use App\Models\modulos;
use App\Models\cuotas;
use Carbon\Carbon;

class DocumentosController extends Controller
{
    public function getDocuments(Request $request)
    {
        $documentos     = clientes::all();
        // $documentos = clientes::join('matriculas as mat','mat.id_cliente','clientes.id_cliente')
        // ->join('modulos as mod','mat.id_matricula','mod.id_matricula')
        // ->get();

        return $documentos;


    }
    public function deleteDocuments(Request $request)
    {

        $archivod       = clientes::where('id_cliente',$request->id)->delete();
        
        if($archivod){

            return response()->json([
                'mensaje' => 'Usuario eliminado',
                'respuesta' => true,
            ],200);

        }else{
            return response()->json([
                'mensaje' => 'Ha ocurrido un error',
                'respuesta' => false
            ],500);
        }
    }

    public function createDocuments(Request $request)
    {
        $archivon   = new cliente();

        $archivon->usuario  = $request->usuario;
        $archivon->correo   = $request->correo;
        $archivon->dni      = $request->dni;
        $archivon->telefono = $request->telefono;
        $archivores         =$archivon->save();

        if($archivores){
            return response()->json([
                'mensaje' => 'Usuario creado',
                'respuesta' => true
            ],200);
        }else{
            return response()->json([
                'mensaje' => 'Ha ocurrido un error',
                'respuesta' => false
            ],500);
        }

    }

    public function getMatriculados(Request $request)
    {
        $matriculados       = matriculas::join('clientes as cli','cli.id_cliente','matriculas.id_cliente')
        ->join('medio_pagos as med','med.id_medio_pago','matriculas.id_medio_pago')
        ->join('modulos as mod','matriculas.id_matricula','mod.id_matricula')
        ->get([
            'cli.usuario',
            'cli.id_cliente',
            'cli.dni',
            'matriculas.deuda',
            'matriculas.estado',
            'matriculas.monto_total',
            'matriculas.activacion',
            'matriculas.formas_acceso_sap',
            'matriculas.multiplicidad',
            'med.nombre as forma_pago',
            'mod.nombre as nombre_modulo',
            'matriculas.id_matricula',
        ]);

        $clientes       = clientes::get([
                                    'usuario',
                                    'id_cliente'
                                    ]);

        foreach($matriculados as $mat){

            $cuotas = cuotas::where('id_matricula',$mat->id_matricula)
                            ->join('medio_pagos as med','med.id_medio_pago','cuotas.id_medio_pago')
                            ->get();
            $mat['cuotas'] = $cuotas;

            $pago_total = 0;

            $cuotasUsuario = cuotas::where('id_matricula',$mat->id_matricula)
                                    ->where('id_estado',0)
                                    ->get();


            foreach($cuotasUsuario as $cuo){
                $pago_total = $pago_total + $cuo->pago;
            }

            $deuda = $mat->monto_total - $pago_total;

            $mat['deuda'] = $deuda;

            if($deuda <= 0){
                $mat['estado'] = 1;
            }else{
                $mat['estado'] = 0;
            }


        }

        $forma_pago     = medio_pagos::all();

        return response()->json([
            'matriculados'  => $matriculados,
            'clientes'      => $clientes,
            'medio_pago'    => $forma_pago
        ],200);
    }

    public function createMatricula(Request $request)
    {
        $matriculan     = new matriculas();

        $matriculan->deuda          = $request->monto_total;
        $matriculan->id_medio_pago  = $request->id_medio_pago;
        $matriculan->estado         = 0;
        $matriculan->monto_total    = $request->monto_total;
        $matriculan->activacion     = $request->activacion;
        $matriculan->multiplicidad  = $request->multiplicidad;
        $matriculan->id_cliente     = $request->id_cliente;
        $matriculan->formas_acceso_sap = $request->formas_acceso_sap;

        $matriculan->save();

        $id_Matricula = $matriculan->id;

        $modulons           = new modulos();
        $modulons->nombre   = $request->nombre;
        $modulons->fecha   = $request->fecha;
        $modulons->id_matricula   = $id_Matricula;

        $modulons->save();

        return response()->json([
            'mensaje'   => 'Matricula creada',
            'respuesta' => true
        ],200);
    }

    public function createCuota(Request $request)
    {

        $id_matricula = $request[0];

        $cuotas = $request[1];

        $cuotasd       = cuotas::where('id_matricula',$request->id_matricula)->delete();

        foreach($cuotas as $cuo){

            if(isset($cuo['pago'])){
                $cuotasn                = new cuotas();
                $cuotasn->numero_cuota  = $cuo['numero_cuota'];
                $cuotasn->fecha         = Carbon::parse($cuo['fecha'])->format('Y-m-d H:i:s');
                $cuotasn->pago          = $cuo['pago'];
                $cuotasn->id_medio_pago = $cuo['medio'];
                $cuotasn->id_matricula	= $id_matricula;
                $cuotasn->alerta        = 0;
                $cuotasn->id_estado     = $cuo['id_estado'];
                $cuotasn->save();
            }
        }

        return response()->json([
            'mensaje' => 'Guardados'
        ]);

    }
}

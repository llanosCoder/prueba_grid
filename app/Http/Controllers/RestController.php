<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Inscripcion;
use Illuminate\Http\Request;

class RestController extends Controller
{
    protected function contarInscripciones()
    {
        $inscripciones = Inscripcion::count();

        return $inscripciones;
    }

    public function registrarInscripcion(Request $request)
    {
        if ($this->contarInscripciones() >= 5) {
            return json_encode(array('respuesta' => false, 'mensaje' => 'El cupo de inscripciones se ha acabado'));
        } 

        $inscripcion = new Inscripcion();

        $inscripcion->nombre = $request->get('nombre');
        $inscripcion->edad = $request->get('edad');
        $inscripcion->fecha_nacimiento = $request->get('fecha_nacimiento');
        $inscripcion->rut = $request->get('rut');

        $respuesta = $inscripcion->save();

        if ($respuesta) {
            $mensaje = '¡Te has inscrito exitosamente!';
        } else {
            $mensaje = 'Ha ocurrido un error en la inscripción';
        }

        return json_encode(array('respuesta' => $respuesta, 'mensaje' => $mensaje));
    }

    public function obtenerTotalInscripciones()
    {
        return json_encode(array('inscripciones' => $this->contarInscripciones()));
    }

}
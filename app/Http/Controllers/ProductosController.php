<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Categorias;
use App\Models\Subcategorias;

class ProductosController extends Controller
{
    public function registrarProducto(Request $request)
    {
        $datos_array = $request->all();

        if (!empty($datos_array)) {
            //Limpiar datos
            $parametros_array = array_map('trim', $datos_array);

            //Seleccionar los datos a validar
            $validador = \Validator::make($parametros_array, [
                'categoria' => 'required',
                'subcategoria' => 'required',
                'producto' => 'required',
                'descripcion' => 'required',
                'precio' => 'required',
                'sku' => 'required|unique:productos',
                'existencia' => 'required',
            ]);
            //Validación
            if ($validador->fails()) {
                //Mensaje de error
                $respuesta = array(
                    'codigo' => 500,
                    'mensaje' => 'Los datos no son correctos.',
                    'payload' => [
                        'errores' => $validador->errors()
                    ]
                );
            } else {
                //Crear producto
                $producto = new Productos();
                $producto->categoria = $parametros_array['categoria'];
                $producto->subcategoria = $parametros_array['subcategoria'];
                $producto->producto = $parametros_array['producto'];
                $producto->precio = $parametros_array['precio'];
                $producto->descripcion = $parametros_array['descripcion'];
                $producto->sku = $parametros_array['sku'];
                $producto->existencia = $parametros_array['existencia'];
                //Guardar producto
                $producto->save();

                //Mensaje de respuesta
                $respuesta = array(
                    'status' => 'correcto',
                    'codigo' => 200,
                    'mensaje' => 'Se creo correctamente el producto.',
                );
            }
        } else {
            //Mensaje de error
            $respuesta = array(
                'status' => 'error',
                'codigo' => 500,
                'mensaje' => 'Informacion no valida.',
            );
        }

        //Retorno de respuesta en json
        return response()->json($respuesta);
    }

    public function traerCategorias()
    {
        $categorias = Categorias::all();

        $respuesta = [
            'code' => 200,
            'message' => 'correcto',
            'payload' => $categorias
        ];

        return response()->json($respuesta);
    }

    public function traerSubcategorias()
    {
        $subcategorias = Subcategorias::all();

        $respuesta = [
            'code' => 200,
            'message' => 'correcto',
            'payload' => $subcategorias
        ];

        return response()->json($respuesta);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Controlador del carrito de compras.
 *
 * El carrito se mantiene en la sesión de Laravel.
 *
 * IMPORTANTE DE SEGURIDAD:
 * El precio NUNCA se acepta desde el formulario enviado por el cliente.
 * Siempre se obtiene directamente desde la base de datos para
 * prevenir manipulación de precios del lado del cliente.
 */
class CartController extends Controller
{
    /**
     * Agrega un producto al carrito de compras.
     *
     * Busca el producto en la BD para obtener el precio real.
     * Si el producto tiene descuento activo, aplica el precio con descuento.
     * Si el producto ya está en el carrito, incrementa la cantidad.
     */
    public function agregar(Request $request, $id)
    {
        // Buscar el producto en la BD — precio SIEMPRE desde la BD
        $producto = Producto::activos()->find($id);

        if (!$producto) {
            return redirect()->back()->withErrors(['carrito' => 'Producto no disponible.']);
        }

        if ($producto->stock <= 0) {
            return redirect()->back()->withErrors(['carrito' => 'Producto sin stock disponible.']);
        }

        $carrito = Session::get('carrito', []);

        if (isset($carrito[$id])) {
            // Si ya está en el carrito, incrementar cantidad
            $carrito[$id]['cantidad'] += 1;
        } else {
            // Calcular precio real (con o sin descuento) desde la BD
            $precioFinal = $producto->precio_final;

            $carrito[$id] = [
                'id'                   => $producto->id,
                'nombre'               => $producto->nombre,
                'marca'                => $producto->marca,
                'precio_original'      => (float) $producto->precio,
                'precio'               => $precioFinal,
                'porcentaje_descuento' => $producto->porcentaje_descuento,
                'imagen'               => $producto->imagen,
                'cantidad'             => 1,
            ];
        }

        Session::put('carrito', $carrito);
        Session::put('carrito_abierto', true);

        return redirect()->back();
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     *
     * Si la cantidad resultante es 0 o menor, el producto se elimina del carrito.
     *
     * @param string $amount Cantidad a sumar/restar (+1 o -1)
     */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'cantidad' => ['required', 'integer'],
        ]);

        $cantidad = (int) $request->input('cantidad');
        $carrito  = Session::get('carrito', []);

        if (isset($carrito[$id])) {
            $nuevaCantidad = $carrito[$id]['cantidad'] + $cantidad;

            if ($nuevaCantidad > 0) {
                $carrito[$id]['cantidad'] = $nuevaCantidad;
            } else {
                // Si queda en 0 o menos, eliminarlo del carrito
                unset($carrito[$id]);
            }

            Session::put('carrito', $carrito);
        }

        Session::put('carrito_abierto', true);

        return redirect()->back();
    }

    /**
     * Elimina un producto específico del carrito.
     */
    public function eliminar($id)
    {
        $carrito = Session::get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            Session::put('carrito', $carrito);
        }

        Session::put('carrito_abierto', true);

        return redirect()->back();
    }

    /**
     * Vacía completamente el carrito de compras.
     */
    public function vaciar()
    {
        Session::forget('carrito');
        Session::put('carrito_abierto', false);

        return redirect()->back();
    }

    /**
     * Alterna el estado abierto/cerrado del drawer del carrito en sesión.
     * Se llama via fetch() desde el JS del frontend al cerrar el drawer.
     */
    public function toggle()
    {
        $estaAbierto = Session::get('carrito_abierto', false);
        Session::put('carrito_abierto', !$estaAbierto);

        return redirect()->back();
    }
}

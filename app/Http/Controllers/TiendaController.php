<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

/**
 * Controlador de la tienda pública.
 *
 * Gestiona la página principal con el catálogo de productos activos,
 * las ofertas reales calculadas desde porcentaje_descuento,
 * el formulario de contacto y el formulario de venta de relojes usados.
 */
class TiendaController extends Controller
{
    /**
     * Muestra la página principal de la tienda.
     *
     * Solo se muestran productos activos. Las ofertas son productos activos
     * que tienen un porcentaje_descuento mayor a 0.
     */
    public function index()
    {
        $productos = Producto::activos()->with('imagenes')->orderBy('nombre')->get();
        $ofertas   = Producto::activos()->enOferta()->with('imagenes')->get();

        return view('shop', compact('productos', 'ofertas'));
    }

    /**
     * Procesa el formulario de contacto.
     *
     * En una implementación futura, aquí se enviaría un correo
     * usando Mail::to(config('tienda.correo_contacto'))->send(...)
     */
    public function contacto(Request $request)
    {
        $request->validate([
            'nombre'  => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'mensaje' => ['required', 'string'],
        ], [
            'nombre.required'  => 'El nombre es obligatorio.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'Ingrese un correo electrónico válido.',
            'mensaje.required' => 'El mensaje es obligatorio.',
        ]);

        // TODO: Implementar envío de correo
        // Mail::to(config('tienda.correo_contacto'))->send(new ContactoMail($request->all()));

        return redirect()->to(url()->previous() . '#contacto')
                         ->with('contacto_exito', '¡Gracias! Hemos recibido tu mensaje y te contactaremos pronto.');
    }

    /**
     * Procesa el formulario de venta de relojes.
     *
     * Registra la solicitud de tasación de un reloj usado del cliente.
     */
    public function venderReloj(Request $request)
    {
        $request->validate([
            'nombre'      => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255'],
            'telefono'    => ['required', 'string', 'max:50'],
            'descripcion' => ['required', 'string'],
        ], [
            'nombre.required'      => 'El nombre es obligatorio.',
            'email.required'       => 'El correo electrónico es obligatorio.',
            'telefono.required'    => 'El teléfono es obligatorio.',
            'descripcion.required' => 'La descripción del reloj es obligatoria.',
        ]);

        // TODO: Implementar envío de notificación al administrador

        return redirect()->to(url()->previous() . '#vender')
                         ->with('vender_exito', '¡Solicitud recibida! Evaluaremos tu reloj y nos comunicaremos contigo a la brevedad.');
    }
}

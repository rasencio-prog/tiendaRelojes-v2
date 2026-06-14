<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración General de la Tienda
    |--------------------------------------------------------------------------
    |
    | Parámetros centralizados de la tienda. Acceder con config('tienda.clave').
    |
    */

    'nombre_tienda'      => env('TIENDA_NOMBRE', 'Rolejería.cl'),

    'correo_contacto'    => env('TIENDA_EMAIL', 'contacto@rolejeria.cl'),

    'telefono_contacto'  => env('TIENDA_TELEFONO', '+56 9 1234 5678'),

    /**
     * Número de WhatsApp para el botón flotante y el checkout.
     * Formato: código de país + número sin espacios ni símbolos.
     * Ejemplo: 56912345678 (Chile)
     */
    'telefono_whatsapp'  => env('TIENDA_WHATSAPP', '56912345678'),

    'direccion'          => env('TIENDA_DIRECCION', 'Av. Vitacura 2808, Las Condes, Santiago, Chile'),

    'facebook'           => env('TIENDA_FACEBOOK', 'https://facebook.com/luxerelojes'),

    'instagram'          => env('TIENDA_INSTAGRAM', 'https://instagram.com/luxerelojes'),

];

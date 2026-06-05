<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Add a product to the cart.
     */
    public function add(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            // Si el producto no se encuentra en la base de datos (por ejemplo,
            // si es de una oferta simulada que tiene un ID en el rango, buscamos por su ID original)
            // Para simplificar, buscamos si el ID coincide. Si no, redirigimos.
            return redirect()->back();
        }

        $cart = Session::get('cart', []);

        // Si el producto ya está en el carrito, incrementar la cantidad
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            // Si no está, agregarlo con cantidad 1
            // Si el producto viene de la sección de Ofertas, el precio podría estar rebajado.
            // Para ser fieles a la UI, comprobamos si la petición indica un precio especial de oferta.
            $price = $product->price;
            if ($request->has('price')) {
                $price = $request->input('price');
            }

            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'brand' => $product->brand,
                'price' => $price,
                'image' => $product->image,
                'quantity' => 1
            ];
        }

        Session::put('cart', $cart);
        Session::put('cart_open', true); // Abrir el carrito automáticamente

        return redirect()->back();
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer'
        ]);

        $amount = (int) $request->input('amount');
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $amount;
            if ($newQty > 0) {
                $cart[$id]['quantity'] = $newQty;
            }
            Session::put('cart', $cart);
        }

        Session::put('cart_open', true);

        return redirect()->back();
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        Session::put('cart_open', true);

        return redirect()->back();
    }

    /**
     * Toggle the cart drawer open/close status in session.
     */
    public function toggle()
    {
        $isOpen = Session::get('cart_open', false);
        Session::put('cart_open', !$isOpen);

        return redirect()->back();
    }
}

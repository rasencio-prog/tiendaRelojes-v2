<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display the main shop view with products and offers.
     */
    public function index()
    {
        $products = Product::all();

        // Las ofertas se simulan tomando los dos primeros productos con un 15% de descuento
        $offers = $products->take(2)->map(function ($product) {
            $offProduct = clone $product;
            $offProduct->originalPrice = $product->price;
            $offProduct->price = $product->price * 0.85;
            return $offProduct;
        });

        return view('shop', compact('products', 'offers'));
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($request->input('username') === 'admin' && $request->input('password') === 'reloj123') {
            Session::put('admin_authenticated', true);
            Session::forget('login_error');
        } else {
            Session::put('login_error', 'Credenciales incorrectas.');
        }

        return redirect()->to(url()->previous() . '#admin');
    }

    /**
     * Handle admin logout.
     */
    public function logout()
    {
        Session::forget('admin_authenticated');
        return redirect()->to(url()->previous() . '#admin');
    }

    /**
     * Store a new product in the database.
     */
    public function store(Request $request)
    {
        if (!Session::get('admin_authenticated')) {
            return redirect()->to(url()->previous() . '#admin')->withErrors(['unauthorized' => 'Acceso no autorizado.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = 'assets/watch_submariner.png'; // Fallback por defecto

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Crear el directorio si no existe
            $destinationPath = public_path('uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $fileName);
            $imagePath = 'uploads/' . $fileName;
        }

        Product::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        Session::flash('admin_success', '¡Reloj agregado exitosamente al catálogo!');

        return redirect()->to(url()->previous() . '#admin');
    }

    /**
     * Handle the contact message submission.
     */
    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Session::flash('contact_success', '¡Gracias! Hemos recibido tu mensaje y te contactaremos pronto.');

        return redirect()->to(url()->previous() . '#contacto');
    }

    /**
     * Handle the sell-watch request form submission.
     */
    public function sellWatch(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'description' => 'required|string',
        ]);

        Session::flash('sell_success', '¡Solicitud recibida! Evaluaremos tu reloj y nos comunicaremos contigo a la brevedad.');

        return redirect()->to(url()->previous() . '#vender');
    }
}

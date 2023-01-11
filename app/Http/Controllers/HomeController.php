<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_shop = Shop::count();
        $total_product = Product::count();
        return view('home', compact('total_shop','total_product'));
    }

    public function postCounter()
    {
        $total_shop = Shop::count();
        $total_product = Product::count();
        return ['shop'=> $total_shop, 'product' => $total_product];
    }

    public function getPattern()
    {
        $alpha = range('A', 'Z');
        $numer = range('1', '100');
        $k = 0;
        for ($i = 0; $i <= 4; $i += 2) {
            for ($j = 3; $j >= 0; $j--) {
                if ($j > $i)
                echo " &nbsp;";
                else
                echo $alpha[$k++] . " ";
            }
            echo "<br>";
        }
        exit;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;

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
        return view('home');
    }

    public function dashboard(){
        $data['2018'] = [23, 19, 0,24, 39, 45, 0,52, 61, 73, 86, 118];
        $data['2019'] = [120, 116, 0,108, 99, 0, 44, 0,35, 22, 17];
        $color[0] = 'red';
        $color[1] = 'blue';
        return response()->json(['ganancias' => $data, 'ventas' => $data, 'color' => $color]);
    }
}

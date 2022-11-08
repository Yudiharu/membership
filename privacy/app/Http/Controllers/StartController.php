<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;

class StartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function index()
    { 
        $Company= Company::get();
        return view('start', compact('Company'));
    }

    public function go_to(Request $request)
    {
        $compa = $request->company;

        return redirect()->route('login', ['kode_company'=>$compa]);
    }
}

<?php
namespace App\Http\Controllers;

use App\Models\Facility;

class HomeController extends Controller
{
    public function index()
    {
        $facilities = Facility::all(); // أو استخدم فلترة حسب النوع
        return view('home', compact('facilities'));
    }
}

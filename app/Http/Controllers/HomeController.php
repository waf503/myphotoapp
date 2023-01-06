<?php

namespace App\Http\Controllers;

use App\Models\Image;


class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $images = Image::orderBy('id','desc')->paginate(2);
        return view('home', ['images'=>$images]);
    }
}

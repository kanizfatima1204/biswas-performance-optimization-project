<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
class HomeController extends Controller { public function index(){ $features = Cache::remember('home.features',300,fn()=>[['title'=>'Production assets','text'=>'Vite builds fingerprinted, minified assets and separates Vue and icon dependencies.'],['title'=>'Responsive layout','text'=>'The interface adapts to narrow screens with a mobile navigation and compact content layout.'],['title'=>'Repeat work','text'=>'The feature list is cached in Laravel for five minutes.']]); return Inertia::render('Home',['features'=>$features]); } }

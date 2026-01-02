<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        $settingsData = DB::table('settings')->pluck('value', 'key')->toArray();
        $setting = (object) $settingsData;
        $features = DB::table('landing_features')->orderBy('sort_order', 'asc')->get();
        $how_it_works = DB::table('landing_steps')->orderBy('sort_order', 'asc')->get();
        $faqs = DB::table('landing_faqs')->orderBy('sort_order', 'asc')->get();
        $majorCount = DB::table('core_majors')->count();
        $studentCount = DB::table('core_students')->count();
        if($majorCount == 0) $majorCount = 5;
        if($studentCount == 0) $studentCount = 1250;

        return view('home', compact('setting', 'features', 'faqs', 'how_it_works', 'majorCount', 'studentCount'));
    }
}

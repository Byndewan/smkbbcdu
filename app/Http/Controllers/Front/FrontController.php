<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\LandingFaq;
use App\Models\LandingFeature;
use App\Models\LandingStep;
use App\Models\Major;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller
{
    public function index()
    {
        $settingsData = DB::table('settings')->pluck('value', 'key')->toArray();
        $setting = (object) $settingsData;
        $features = LandingFeature::orderBy('sort_order', 'asc')->get();
        $how_it_works = LandingStep::orderBy('sort_order', 'asc')->get();
        $faqs = LandingFaq::orderBy('sort_order', 'asc')->get();

        // Count pakai Eloquent (bisa dicache nanti kalau mau performa tinggi)
        $majorCount = Major::count();
        $studentCount = Student::count();
        if ($majorCount == 0) {
            $majorCount = 5;
        }
        if ($studentCount == 0) {
            $studentCount = 1250;
        }

        return view('home', compact('setting', 'features', 'faqs', 'how_it_works', 'majorCount', 'studentCount'));
    }
}

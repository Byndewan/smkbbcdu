<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();
        $setting = (object) $settings;
        $counts = [
            'features' => DB::table('landing_features')->count(),
            'steps' => DB::table('landing_steps')->count(),
            'faqs' => DB::table('landing_faqs')->count(),
        ];
        return view('Settings::front.index', compact('setting', 'counts'));
    }

    public function update(Request $request)
    {
        $keys = [
            'hero_title', 'hero_heading', 'hero_sort_desc',
            'phone', 'footer_description', 'footer_copyright',
            'hero_button_name', 'hero_button_link',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $request->input($key), 'updated_at' => now()]
                );
            }
        }

        $this->handleFileUpload($request, 'logo');
        $this->handleFileUpload($request, 'hero_image');
        $this->handleFileUpload($request, 'favicon');

        return redirect()->back()->with('success', 'Tampilan Landing Page berhasil diperbarui!');
    }

    private function handleFileUpload($request, $keyName)
    {
        if ($request->hasFile($keyName)) {
            $file = $request->file($keyName);
            $path = $file->store('uploads/front-assets', 'public');
            DB::table('settings')->updateOrInsert(
                ['key' => $keyName],
                ['value' => 'storage/'.$path, 'type' => 'image', 'updated_at' => now()]
            );
        }
    }

    public function getData($type)
    {
        if ($type == 'features') {
            $query = DB::table('landing_features')->orderBy('sort_order', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('features_image', function ($row) {
                    return '<img src="'.asset($row->features_image).'" width="50" class="rounded">';
                })
                ->addColumn('action', function ($row) {
                    return $this->getActionButtons('feature', $row);
                })
                ->rawColumns(['features_image', 'action'])->make(true);
        }

        if ($type == 'steps') {
            $query = DB::table('landing_steps')->orderBy('sort_order', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('how_icon', function ($row) {
                    return '<i class="'.$row->how_icon.' fs-4 text-danger"></i>';
                })
                ->addColumn('action', function ($row) {
                    return $this->getActionButtons('step', $row);
                })
                ->rawColumns(['how_icon', 'action'])->make(true);
        }

        if ($type == 'faqs') {
            $query = DB::table('landing_faqs')->orderBy('sort_order', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return $this->getActionButtons('faq', $row);
                })
                ->rawColumns(['action'])->make(true);
        }
    }

    private function getActionButtons($type, $row)
    {
        $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

        return '
            <div class="btn-group btn-group-sm gap-1">
                <button type="button" class="btn btn-info text-white btn-preview" data-type="'.$type.'" data-row="'.$jsonData.'"><i class="bi bi-eye"></i></button>
                <button type="button" class="btn btn-warning text-white btn-edit" data-type="'.$type.'" data-row="'.$jsonData.'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-danger btn-delete" data-url="'.route('settings.front.'.$type.'.destroy', $row->id).'"><i class="bi bi-trash"></i></button>
            </div>';
    }

    public function saveFeature(Request $request)
    {
        $data = [
            'features_card_heading' => $request->heading,
            'features_card_sort_desc' => $request->desc,
            'sort_order' => $request->sort_order ?? 0,
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            $data['features_image'] = 'storage/'.$request->file('image')->store('uploads/front-assets', 'public');
        }

        if ($request->id) {
            DB::table('landing_features')->where('id', $request->id)->update($data);
            $msg = 'Fitur diperbarui';
        } else {
            $data['created_at'] = now();
            if (! $request->hasFile('image')) {
                return back()->with('error', 'Gambar wajib diupload');
            }
            DB::table('landing_features')->insert($data);
            $msg = 'Fitur ditambahkan';
        }

        return back()->with('success', $msg);
    }

    public function destroyFeature($id)
    {
        DB::table('landing_features')->where('id', $id)->delete();

        return response()->json(['message' => 'Fitur berhasil dihapus']);
    }

    public function saveStep(Request $request)
    {
        $data = [
            'how_icon' => $request->icon,
            'how_item_heading' => $request->heading,
            'how_item_sort_desc' => $request->desc,
            'sort_order' => $request->sort_order ?? 0,
            'updated_at' => now(),
        ];

        if ($request->id) {
            DB::table('landing_steps')->where('id', $request->id)->update($data);
            $msg = 'Langkah diperbarui';
        } else {
            $data['created_at'] = now();
            DB::table('landing_steps')->insert($data);
            $msg = 'Langkah ditambahkan';
        }

        return back()->with('success', $msg);
    }

    public function destroyStep($id)
    {
        DB::table('landing_steps')->where('id', $id)->delete();

        return response()->json(['message' => 'Langkah panduan berhasil dihapus']);
    }

    public function saveFaq(Request $request)
    {
        $data = [
            'faq_card_question' => $request->question,
            'faq_card_answer' => $request->answer,
            'faq_card_icon' => $request->icon,
            'faq_card_title' => $request->title,
            'sort_order' => $request->sort_order ?? 0,
            'updated_at' => now(),
        ];

        if ($request->id) {
            DB::table('landing_faqs')->where('id', $request->id)->update($data);
            $msg = 'FAQ diperbarui';
        } else {
            $data['created_at'] = now();
            DB::table('landing_faqs')->insert($data);
            $msg = 'FAQ ditambahkan';
        }

        return back()->with('success', $msg);
    }

    public function destroyFaq($id)
    {
        DB::table('landing_faqs')->where('id', $id)->delete();

        return response()->json(['message' => 'FAQ berhasil dihapus']);
    }
}

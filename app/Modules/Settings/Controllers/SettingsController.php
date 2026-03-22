<?php

namespace App\Modules\Settings\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LandingFaq;
use App\Models\LandingFeature;
use App\Models\LandingStep;
use App\Services\FileService;
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
            'features' => LandingFeature::count(),
            'steps' => LandingStep::count(),
            'faqs' => LandingFaq::count(),
        ];

        return view('Settings::front.index', compact('setting', 'counts'));
    }

    public function update(Request $request, FileService $fileService)
    {
        $keys = [
            'hero_title',
            'hero_heading',
            'hero_sort_desc',
            'phone',
            'footer_description',
            'footer_copyright',
            'hero_button_name',
            'hero_button_link',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $request->input($key), 'updated_at' => now()]
                );
            }
        }

        $this->handleFileUpload($request, 'logo', $fileService);
        $this->handleFileUpload($request, 'hero_image', $fileService);
        $this->handleFileUpload($request, 'favicon', $fileService);

        return redirect()->back()->with('success', 'Tampilan Landing Page berhasil diperbarui!');
    }

    private function handleFileUpload($request, $keyName, FileService $fileService)
    {
        if ($request->hasFile($keyName)) {
            $oldSetting = DB::table('settings')->where('key', $keyName)->first();

            if ($oldSetting && $oldSetting->value) {
                $relativePath = str_replace('storage/', '', $oldSetting->value);
                $fileService->delete($relativePath);
            }

            $path = $fileService->upload($request->file($keyName), 'uploads/front-assets');
            DB::table('settings')->updateOrInsert(
                ['key' => $keyName],
                ['value' => 'storage/' . $path, 'type' => 'image', 'updated_at' => now()]
            );
        }
    }

    public function getData($type)
    {
        if ($type == 'features') {
            $query = LandingFeature::query()->orderBy('sort_order', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('features_image', fn($row) => '<img src="' . asset($row->features_image) . '" width="50" class="rounded">')
                ->addColumn('action', fn($row) => $this->getActionButtons('feature', $row))
                ->rawColumns(['features_image', 'action'])->make(true);
        }

        if ($type == 'steps') {
            $query = LandingStep::query()->orderBy('sort_order', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('how_icon', fn($row) => '<i class="' . $row->how_icon . ' fs-4 text-danger"></i>')
                ->addColumn('action', fn($row) => $this->getActionButtons('step', $row))
                ->rawColumns(['how_icon', 'action'])->make(true);
        }

        if ($type == 'faqs') {
            $query = LandingFaq::query()->orderBy('sort_order', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', fn($row) => $this->getActionButtons('faq', $row))
                ->rawColumns(['action'])->make(true);
        }
    }

    private function getActionButtons($type, $row)
    {
        $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

        return '
            <div class="btn-group btn-group-sm gap-1">
                <button type="button" class="btn btn-warning text-white btn-edit" data-type="' . $type . '" data-row="' . $jsonData . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-danger btn-delete" data-url="' . route('admin.settings.front.' . $type . '.destroy', $row->id) . '"><i class="bi bi-trash"></i></button>
            </div>';
    }

    public function saveFeature(Request $request, FileService $fileService)
    {
        $data = [
            'features_card_heading'   => $request->heading,
            'features_card_sort_desc' => $request->desc,
            'sort_order'              => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($request->id) {
                $existingFeature = LandingFeature::find($request->id);
                if ($existingFeature && $existingFeature->features_image) {
                    $oldPath = str_replace('storage/', '', $existingFeature->features_image);
                    $fileService->delete($oldPath);
                }
            }

            $path = $fileService->upload($request->file('image'), 'uploads/front-assets');
            $data['features_image'] = 'storage/' . $path;
        }

        if ($request->id) {
            LandingFeature::where('id', $request->id)->update($data);
            $msg = 'Fitur diperbarui';
        } else {
            if (!$request->hasFile('image')) {
                return back()->with('error', 'Gambar wajib diupload');
            }

            LandingFeature::create($data);
            $msg = 'Fitur ditambahkan';
        }

        return back()->with('success', $msg);
    }

    public function destroyFeature($id)
    {
        LandingFeature::destroy($id);

        return response()->json(['message' => 'Fitur berhasil dihapus']);
    }

    public function saveStep(Request $request)
    {
        $data = [
            'how_icon' => $request->icon,
            'how_item_heading' => $request->heading,
            'how_item_sort_desc' => $request->desc,
            'sort_order' => $request->sort_order ?? 0,
        ];

        LandingStep::updateOrCreate(['id' => $request->id], $data);

        return back()->with('success', 'Langkah panduan disimpan');
    }

    public function destroyStep($id)
    {
        LandingStep::destroy($id);

        return response()->json(['message' => 'Langkah berhasil dihapus']);
    }

    public function saveFaq(Request $request)
    {
        $data = [
            'faq_card_question' => $request->question,
            'faq_card_answer' => $request->answer,
            'faq_card_icon' => $request->icon,
            'faq_card_title' => $request->title,
            'sort_order' => $request->sort_order ?? 0,
        ];

        LandingFaq::updateOrCreate(['id' => $request->id], $data);

        return back()->with('success', 'FAQ disimpan');
    }

    public function destroyFaq($id)
    {
        LandingFaq::destroy($id);

        return response()->json(['message' => 'FAQ berhasil dihapus']);
    }
}

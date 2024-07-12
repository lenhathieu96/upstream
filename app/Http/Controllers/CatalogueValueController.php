<?php

namespace App\Http\Controllers;

use App\Models\CatalogueValue;
use Illuminate\Http\Request;

class CatalogueValueController extends Controller
{
    public function index(Request $request)
    {
        $code = $request->input('code');
        $name = $request->input('name');
        $display_name = $request->input('display_name');
        $status = $request->input('status');

        $catalogueValueQuery = CatalogueValue::orderByDesc('updated_at')->orderByDesc('CODE');

        if (!empty($code)) {
            $catalogueValueQuery->where('CODE', $code);
        }

        if (!empty($name)) {
            $words = $this->split_with_whitespace($name);
            $catalogueValueQuery->where(function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->where('NAME', 'LIKE', '%' . $word . '%');
                }
            });
        }

        if (!empty($display_name)) {
            $words = $this->split_with_whitespace($display_name);
            $catalogueValueQuery->where(function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->where('DISP_NAME', 'LIKE', '%' . $word . '%');
                }
            });
        }

        if (!is_null($status)) {
            $catalogueValueQuery->where('STATUS', $status);
        }

        $catalogueValues = $catalogueValueQuery->paginate()->appends($request->except('page'));

        return view('catalogue_value.index', compact('catalogueValues', 'code', 'name', 'display_name', 'status'));
    }

    public function split_with_whitespace($keyword)
    {
        return preg_split('/\s+/u', $keyword, -1, PREG_SPLIT_NO_EMPTY);
    }
}

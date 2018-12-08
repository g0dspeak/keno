<?php

namespace App\Http\Controllers;

use App\Keno\Game;
use App\Keno\WinsTable;
use App\Model\KenoWinsGenerator;
use Illuminate\Http\Request;

class KenoUsageController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $winsTable = WinsTable::WINS_TABLE;
        $promptOption = '<option value="0" disabled>Choose</option>';

        $jsFilesHeader = [asset('/js/custom/formHelper.js'), asset('/js/custom/form.js')];
        $jsFilesFooter = [];
        $cssFiles = [asset('/css/numbers_combination.css'), asset('/css/custom/form.css'), asset('/css/custom/table.css')];

        return view('keno.index', compact('winsTable', 'promptOption', 'cssFiles', 'jsFilesFooter', 'jsFilesHeader'));
    }


    public function countResults(Request $request)
    {
        set_time_limit(90);

        $keno = new Game($request->all());

        return response()->json($keno->play()->getResults());
    }
}

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

        $jsFilesHeader = [url('/js/custom/formHelper.js'), url('/js/custom/form.js')];
        $jsFilesFooter = [];
        $cssFiles = [url('/css/numbers_combination.css'), url('/css/custom/form.css'), url('/css/custom/table.css')];

        return view('keno.index', compact('winsTable', 'promptOption', 'cssFiles', 'jsFilesFooter', 'jsFilesHeader'));
    }


    public function countResults(Request $request)
    {
        set_time_limit(90);

        $data = $request->all();

        $keno = new Game($data);
        $keno->play();

        echo json_encode($keno->results);
    }
}

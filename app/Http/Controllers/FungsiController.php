<?php

namespace App\Http\Controllers;

class FungsiController extends Controller
{
    public function get()
    {
        $result = app('db')->select('select * from fungsi');
        return response()->json($result);
    }

}

<?php

namespace App\Http\Controllers;
use App\Services\DataBaseConnection;

use Illuminate\Http\Request;

class DataBaseController extends Controller
{
    function my()
    {
        $coll=new DataBaseConnection();
        $coll2=$coll->my2();
        //dd($coll2);
        $insert=$coll2->insertOne([
            'name'=>'hamza',
        ]);
        dd($insert);
    }
}


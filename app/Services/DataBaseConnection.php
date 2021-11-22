<?php

namespace App\Services;
use MongoDB\Client as mongo;

use Illuminate\Http\Request;

class DataBaseConnection
{
    function connect()
    {
        $collection= (new mongo)->MongoDB;
        return $collection;
    }
}

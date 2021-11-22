<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\DataBaseConnection;
class Authenticatemiddele
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    /**
     * handle function check jwt toke is valid or not
     * if token is valid then proceed next request 
     */
    public function handle(Request $request, Closure $next)
    {
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'remember_token'=> $request->token,
        ));
        if(!empty($find))
        {
            return $next($request);
        }
        else{
            return response(['Message'=>'You Are Logout']);
        }
    }
}

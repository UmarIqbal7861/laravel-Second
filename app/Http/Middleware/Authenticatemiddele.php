<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = DB::table('users')->where('remember_token', $request->token)->get();//check token database querie
        $check=count($data);
        if($check>0)
        {
            return $next($request);
        }
        else{
            return response(['Message'=>'You Are Logout']);
        }
    }
}

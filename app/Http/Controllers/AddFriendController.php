<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AddFriendValidation;
use App\Http\Requests\RemoveFriendValidation;
use App\Models\Friend;

class AddFriendController extends Controller
{
    /**
     * add_friend function
     * this function add one user to another user if the user exit 
     */
    function add_friend(AddFriendValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //database querie
        $check=count($data);    
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->u_id;
            $data1 = DB::table('users')->where('email', $req->email)->get();    //database querie
            $check1=count($data1);
            $ver=$data1[0]->email_verified_at;
            if(!empty($ver))    //if condition check the user mail is verfiy or not 
            {
                if($check1>0) //if condition check 2nd user exit or not
                {   
                    $id2=$data1[0]->u_id;
                    if($id1==$id2)      //if condition check not add your self
                    {
                        return response(['Message'=>'You cannot add your self as friend.']);
                    }
                    else{
                        $val=array('user1'=>$id1,'user2'=>$id2);
                        DB::table('friends')->insert($val);     //database querie
                        return response(['Message'=>'Friend Add']);
                    }
                }
                else{
                    return response(['Message'=>'Friend not Found']);
                }
            }
            else{
                return response(['Message'=>'Friend not Found']);
            }
        }
        else{
            return response(['Message'=>'Please login Account']);
        }
    }
    function removeFriend(RemoveFriendValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //database querie
        $check=count($data);    
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->u_id;
            $data1 = DB::table('users')->where('email', $req->email)->get();    //database querie
            $check1=count($data1);
            if($check1>0) //if condition check 2nd user exit or not
            {   
                $id2=$data1[0]->u_id;
                $data3=DB::table('friends')->where(['user1'=> $id1 , 'user2' =>$id2])->delete();
                if($data3)
                {
                    return response(['Message'=>'Friend Remove Conform']);
                }
                else{
                    return response(['Message'=>'You are not Friend This user ']);
                }
            }
            else{
                return response(['Message'=>'Account Not exists']);
            }
        }
    }
}
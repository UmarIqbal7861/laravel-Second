<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\comment;

use App\Http\Requests\CommentValidation;
use App\Http\Requests\CommentDeleteValidation;
use App\Http\Requests\CommentUpdateValidation;

class CommentController extends Controller
{
    /**
     * this controller for use the comments table 
     */
    /**
     * this controller create comment,update comment,delete comment
     */
    function comment(CommentValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            $id1=$data[0]->u_id;
            $file=$req->file('file')->store('comment');    //store comment
            $val=array('user_id'=>$id1,'post_id'=>$req->pid,'comment'=>$req->comment,'file'=>$file);
            DB::table('comments')->insert($val);       //database querie
            return response(['Message'=>'Comment Success']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function commentupdate(CommentUpdateValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            $id=$data[0]->u_id;
            $file=$req->file('file')->store('comment');    //store comment
            $ch=DB::table('comments')->where(['c_id'=> $req->cid,'user_id'=>$id])->update(['comment'=>$req->comment,'file'=> $file]);    //database querie   //database querie 
            if($ch)
            {
                return response(['Message'=>'Data Update']);
            }
            else{
                return response(['Message'=>'Not Allow to Update any other person comment']);
            }
            
        }
    }
    function commentDelete(CommentDeleteValidation $req)
    {
        $req->validated();
        $data = DB::table('users')->where('remember_token', $req->token)->get();
        $check=count($data);
        if($check>0)    //if condition check user is login in or not
        {
            $id=$data[0]->u_id;
            $ch=DB::table('comments')->where(['c_id'=> $req->cid , 'user_id' =>$id])->delete(); //database querie
            if($ch)
            {
                return response(['Message'=>'Data Update']);
            }
            else{
                return response(['Message'=>'Not Allow to Delet any other person post']);
            }
        }
    }
}

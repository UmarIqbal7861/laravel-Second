<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\comment;
use App\Services\DataBaseConnection;
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
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'remember_token'=> $req->token,
        ));
        if(!empty($find))    //if condition check user is login in or not
        {
            $user_id=$find['_id'];
            $table='posts';
            $file=$req->file('file')->store('comment');    //store comment
            $id=new \MongoDB\BSON\ObjectId($req->pid);
            $comment_id=new \MongoDB\BSON\ObjectId();
            $comment=array('_id'=>$comment,'user_id'=>$user_id,'comment'=>$req->comment,'file'=>$file);
            $update=$DB->$table->updateOne(["_id"=>$id],['$push'=>["comments" => $comment]]);
            if(!empty($update))
            {
                return response(['Message'=>'Comment Success']);
            }
            else{
                return response(['Message'=>'Post Not Exists']);
            }
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function commentupdate(CommentUpdateValidation $req)
    {
        $req->validated();
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'remember_token'=> $req->token,
        ));
        //$data = DB::table('users')->where('remember_token', $req->token)->get();
        //$check=count($data);
        if(!empty($find))    //if condition check user is login in or not
        {
            //$id=$data[0]->u_id;
            $user_id=$find['_id'];
            $file=$req->file('file')->store('comment');    //store comment
            
            //$ch=DB::table('comments')->where(['c_id'=> $req->cid,'user_id'=>$id])->update(['comment'=>$req->comment,'file'=> $file]);    //database querie   //database querie 
            $id=new \MongoDB\BSON\ObjectId($req->cid);
            $comment=array('comment'=>$req->comment,'file'=>$file);
            $update=$DB->$table->updateOne(['_id'=>$id,'user_id'=>$user_id],['$push'=>['comments' => $comment]]);
            if(!empty($update))
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

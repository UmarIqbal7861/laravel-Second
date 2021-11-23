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
            $comment=array('_id'=>$comment_id,'user_id'=>$user_id,'comment'=>$req->comment,'file'=>$file);
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
        if(!empty($find))    //if condition check user is login in or not
        {
            $user_id=$find['_id'];
            $file=$req->file('file')->store('comment');    //store comment
            $cid=new \MongoDB\BSON\ObjectId($req->cid);
            $pid=new \MongoDB\BSON\ObjectId($req->pid);
            $table='posts';
            $update=$DB->$table->updateOne(['_id'=>$pid, 'comments._id'=>$cid], 
                ['$set'=>['comments.$.comment'=>$req->comment,'comments.$.file'=>$file]]);
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
            $cid=new \MongoDB\BSON\ObjectId($req->cid);
            $pid=new \MongoDB\BSON\ObjectId($req->pid);
            $delete=$DB->$table->updateOne(['_id'=>$pid, 'comments._id'=>$cid], 
                ['$pull'=>['comments'=>['_id'=>$cid]]]);
            if(!empty($delete))
            {
                return response(['Message'=>'Comment Delete']);
            }
            else{
                return response(['Message'=>'Not Allow to Delet any other person post']);
            }
        }
    }
}

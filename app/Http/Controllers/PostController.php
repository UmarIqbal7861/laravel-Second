<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostValidation;
use App\Http\Requests\PostUpdateValidation;
use App\Http\Requests\PostDeleteValidation;
use App\Http\Requests\SearchPostValidation;
use App\Http\Requests\FindPostValidation;
use App\Services\DataBaseConnection;


class PostController extends Controller
{
    /**
     * this controller for use the post table 
     */
    /**
     * this controller create post,update post,view all post ,and also delete post
     */
    function post(PostValidation $req)
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
            $file=$req->file('file')->store('post');    //store post
            $table='posts';
            $document=array('user_id'=>$user_id,'file'=>$file,'access'=>$req->access);
            $insert=$DB->$table->insertOne($document);
            return response(['Message'=>'Post Success']);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    
    function postupdate(PostUpdateValidation $req)
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
            $file=$req->file('file')->store('post');    //store post
            $table='posts';
            $id=new \MongoDB\BSON\ObjectId($req->pid);
            $update=$DB->$table->updateMany(array('_id'=> $id,'user_id'=>$user_id), 
                array('$set'=>array('file'=> $file,'access'=> $req->access)));
            if(!empty($update))
            {
                return response(['Message'=>'Data Update']);
            }
            else{
                return response(['Message'=>'Not Allow to update any other person post']);
            }
        }
    }

    function postdelete(PostDeleteValidation $req)
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
            $id=new \MongoDB\BSON\ObjectId($req->pid);
            $delete=$DB->$table->deleteOne(array('_id'=> $id,'user_id'=>$user_id));
            if(!empty($delete))
            {
                return response(['Message'=>'Data Delete']);
            }
            else{
                return response(['Message'=>'Not Allow to Delete any other person post']);
            }
        }
    }
    /**
     * checkFriend function check friend exists in friend list
     */
    function checkFriend($itset,$friend_id)
    {
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            '_id'=> $itset,
        ));
        $friend=$find['friends'];
        foreach($friend as $key)
        {
            if($friend_id==$key['friend'])
            {
                return true;
            }
        }
        return false;
    }

    function read(Request $req)
    {
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'remember_token'=> $req->token,
        ));
        if(!empty($find))
        {
            $user_id=$find['_id'];
            $table='posts';
            $find2=$DB->$table->find(array(
                'access'=>'public'
            ));
            if(!empty($find2))
            {
                $objects = json_decode(json_encode($find2->toArray(),true));
                echo json_encode($objects);
            }
            $find3=$DB->$table->find(array(
                'access'=>'private'
            ));
            if(!empty($find3))
            {
                foreach($find3 as $key1)
                {
                    $user2_id=$key1['_id'];
                    if($this->checkFriend($user_id,$user2_id))
                    {
                        echo json_encode($key1);
                    }
                }
            }
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
    function findpost(FindPostValidation $req)
    {
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'remember_token'=> $req->token,
        ));
        if(!empty($find))
        {
            $user_id=$find['_id'];
            $table='posts';
            $id=new \MongoDB\BSON\ObjectId($req->pid);
            $find=$DB->$table->findOne(array(
                '_id'=>$id,
            ));
            if(!empty($find))
            {
                $access=$find['access'];
                if($access=='public')
                {
                    echo json_encode($find);
                }
                else{
                    
                    $user2_id=$find['_id'];
                    
                    if($this->checkFriend($user_id,$user2_id))
                    {
                        echo json_encode($find);
                    }
                    else{
                        return response(['Message'=>'Post Not Found!!']);
                    }
                }
            }
        }
    }
}
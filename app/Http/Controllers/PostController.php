<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostValidation;
use App\Http\Requests\PostUpdateValidation;
use App\Http\Requests\PostDeleteValidation;
use App\Http\Requests\SearchPostValidation;
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
    function checkFriend($itset,$friend_id){
        $r = DB::table('friends')->where('user1',$itset)->where('user2',$friend_id)->get();
        if (count($r) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function read(Request $req)
    {
        $data = DB::table('users')->where('remember_token', $req->token)->get();    //database querie
        $check=count($data);    //if condition check user is login in or not
        if($check>0)
        {
            $id2=$data[0]->u_id;
            $data=DB::table('posts')->where(['access'=>'public'])->get();    //access the public post
            foreach($data as $key1)
            {
                $pid = $key1->p_id;
                print_r ($key1);
                $da=DB::table('comments')->where('post_id',$pid)->get();
                print_r($da);
            }
            $data2=DB::table('posts')->where(['access'=>'private'])->get();  //access the private post
            foreach($data2 as $key)
            {
                $id = $key->user_id;
                $pid = $key->p_id;
                if($this->checkFriend($id2,$id))
                {
                    print_r ($key);
                    $da=DB::table('comments')->where('post_id',$pid)->get();
                    print_r($da);
                }
            }
            //return response([$data]);
        }
        else{
            return response(['Message'=>'Please login First!!']);
        }
    }
}
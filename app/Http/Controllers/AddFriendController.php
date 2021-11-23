<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AddFriendValidation;
use App\Http\Requests\RemoveFriendValidation;
use App\Models\Friend;
use App\Services\DataBaseConnection;

class AddFriendController extends Controller
{
    /**
     * add_friend function
     * this function add one user to another user if the user exit 
     */
    function add_friend(AddFriendValidation $req)
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
            $find2=$DB->$table->findOne(array(
                'email'=> $req->email,
            ));
            if(!empty($find2))
            {
                $user2_id=$find2['_id'];
                $ver=$find2['email_verified_at'];
                if(!empty($ver))
                {
                    if($user_id==$user2_id)      //if condition check not add your self
                    {
                        return response(['Message'=>'You cannot add your self as friend.']);
                    }
                    else{
                        $check2=true;
                        $check=$find['friends'];
                        foreach($check as $key)
                        {
                            if($user2_id==$key['friend'])
                            {
                                $check2=false;
                                break;
                            }
                        }
                        if($check2==true)
                        {
                            $add=array('friend'=>$user2_id);
                            $add2=array('friend'=>$user_id);
                            $update=$DB->$table->updateOne(["_id"=>$user_id],['$push'=>["friends" => $add]]);
                            $update=$DB->$table->updateOne(["_id"=>$user2_id],['$push'=>["friends" => $add2]]);
                            if(!empty($update))
                            {
                                return response(['Message'=>'Friend Add']);
                            }
                            else{
                                return response(['Message'=>'Friend Not Add']);
                            }
                        }
                        else{
                            return response(['Message'=>'Already Friend']);
                        }
                        
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
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'remember_token'=> $req->token,
        ));     
        if(!empty($find))    //if condition check user is login in or not
        {
            $user_id=$find['_id'];
            $find2=$DB->$table->findOne(array(
                'email'=> $req->email,
            ));  
            if(!empty($find2)) //if condition check 2nd user exit or not
            {   
                $user2_id=$find2['_id'];
                $delete=$DB->$table->updateOne(['_id'=>$user_id, 'friends.friend'=>$user2_id], 
                ['$pull'=>['friends'=>['friend'=>$user2_id]]]);
                if(!empty($delete))
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
<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SignUpValidation;
use App\Http\Requests\LogInValidation;
use App\Http\Requests\ForgetValidation;
use App\Http\Requests\ChangePasswordValidation;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendmail;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Services\DataBaseConnection;


class UserController extends Controller
{
    public $mail;
    /**
     * sign_up function
     * sign up get data through postman and chech email already exit or not 
     * if not exit then check all the add and update the user table
     */
    function signUp(SignUpValidation $req)
    {
        $req->validated();
        $mail;

        $create=new DataBaseConnection();
        $DB=$create->connect();
        $document = array(
            'name'=>$req->input('Name'),
            'email'=>$mail=$req->input('Email'),
            'password'=>Hash::make($req->input('Password')),
            'gender'=>$req->input('Gender'),
            'profile'=>$req->file('Profile')->store('Profile_pic'),
            'status'=>0,
            'token'=>$token=rand(100,1000),
            'email_verified_at'=> 0,
            'friends'=> 0,
        );
        $table='users';
        $find=$DB->$table->findOne(array(
            'email'=>$mail
        ));
        if(empty($find))
        {
            $insert=$DB->$table->insertOne($document);
            if(!empty($insert))
            {
                $mess=$this->sendMail($mail,$token);    //call send mail function 
                return response()->json(['Message' =>  $mess],200);
            }
        }
        else{
            return response()->json(['Message'=>'Mail Already exist..!!!']);
        }
    }
    /**
     * sendmail function 
     * send mail with the link of verfiy link 
     */
    function sendMail($mail,$token)
    {
        $details=[
            'title'=> 'SignUp Verification',
            'body'=> 'This Link use for login http://127.0.0.1:8000/user/verfi/email/123/ver/'.$mail.'/'.$token
        ]; 
        Mail::to($mail)->send(new sendmail($details));
        return "Check Your Mail.";
    }
    /**
     * this jwtToken generate the jwt toke and return jwt Token
     */
    function jwtToken()
    {
        $key = "umari4042";
        $payload = array(
            "iss" => "localhost",
            "aud" => "users",
            "iat" => time(),
            "exp" => time()+1800,
            "nbf" => 1357000000
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
    /**
     * Verification function
     * check the user email valid is not through the email link
     */
    function Verification($mail,$token)
    {
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'email'=>$mail,
            'token'=>(int)$token
        ));
        //  $a=$insert['_id'];
        // $id=new \MongoDB\BSON\ObjectId($a);
        if(!empty($find))
        {
            $update=$DB->$table->updateMany(array("email"=>$mail), 
            array('$set'=>array('email_verified_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'))));
            return response(['Message' => 'Your Account has been Verified.']);
        }
        else{
            return "Your Email is not Verfiy :";
        }
    }
    /**
     * login function 
     * this function login the user if the user exit in the database and also verfiy the account
     * when the user login we generate jwt token and store in the database
     * this function get data through the postman in the post methord
     */
    public function login(LogInValidation $req)
    {
        $req->validated();
        $email = $req->input('Email');
        $password = $req->input('Password');
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'email'=>$email,
        ));
        if(!empty($find))
        {
            $passwordGet = $find['password'];
            $status = $find['status'];
            $verfi =$find['email_verified_at'];
            if(!empty($verfi))
            {
                if(Hash::check($password,$passwordGet))
                {
                    if($status == 1)
                    {
                        $jwt=$this->jwtToken();
                        $update=$DB->$table->updateMany(array("email"=>$email), 
                            array('$set'=>array('remember_token'=> $jwt)));
                        return response(['Message'=>'You are already logged in..!','Access_Token'=>$jwt]);                    
                    }
                    else{
                        $jwt=$this->jwtToken();
                        $update=$DB->$table->updateMany(array("email"=>$email), 
                            array('$set'=>array('remember_token'=> $jwt,'status' => 1)));
                        return response(['Message'=>'Now you are logged In','Access_Token'=>$jwt]);     //return response
                    }
                }
                else{
                    return response(['Message'=>'Password Does Not Match']);
                }
            }
            else{
                return response(['Message'=>'Your Email is not Verified. Please Verify your email first.']);
            }
        }
        else{
            return response(['Message'=>'Data does not exists']); 
        }
    }
    /**
     * update function 
     * get data from the postman (post) and updata name,gender,passwor and profile
     * get all data and update in data base
     */
    function update(Request $req)
    {
        $file=$req->file('file')->store('Profile_pic'); //store profile pic
        $pass=Hash::make($req->password);   //convert password in hash

        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $update=$DB->$table->updateMany(array("remember_token"=>$req->token), 
            array('$set'=>array('name'=> $req->name,
                'gender'=> $req->gender,'password'=> $pass,'profile'=>$file)));
        if(!empty($update))
        {
            return response(['Message'=>'Data Update']);
        }else{
            return response(['Message'=>'Data Not Update']);
        }
        
    }
    /**
     * logout function 
     * logout function logout the user through the token
     * and update the status = 0 and remember_token= null
     */
    function logout(Request $req)
    {
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $update=$DB->$table->updateMany(array("remember_token"=>$req->token), 
            array('$set'=>array('status'=> 0,'remember_token'=> null)));

        if(!empty($update))
        {
            return response(['Message'=>'Logout']);
        }
        else{
            return response(['Message'=>'Error:::']);
        }
    }
    /**
     * forgetPassword function forget the password through otp 
     * generate otp and send sendMailForgetPassword function 
     */
    function forgetPassword(ForgetValidation $req)
    {
        $req->validated();
        $email=$req->email;
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'email'=>$email,
        ));
        if(!empty($find))
        {
            $verfi =$find['email_verified_at'];
            if(!empty($verfi))
            {
                $otp=rand(1000,9999);
                $update=$DB->$table->updateMany(array('email'=> $email), 
                array('$set'=>array('token'=> $otp)));
                return response($this->sendMailForgetPassword($email,$otp));
            }
            else{
                return response(['Message'=>'User not Exists']);
            }
        }
        else{
            return response(['Message'=>'User not Exists']);
        }
    }
    /**
     * sendmail function 
     * send mail with the link of for forget the password 
     */
    function sendMailForgetPassword($mail,$otp)
    {
        $details=[
            'title'=> 'Forget Password Verification',
            'body'=> 'Your OTP is '. $otp . ' Please copy and paste the change Password Api'
        ]; 
        Mail::to($mail)->send(new sendmail($details));
        return "Mail send.";
    }
    /**
     * changePassword function change password if otp match
     */
    function changePassword(ChangePasswordValidation $req)
    {
        $req->validated();
        $email=$req->email;
        $token=$req->otp;
        $pass=Hash::make($req->password);
        $create=new DataBaseConnection();
        $DB=$create->connect();
        $table='users';
        $find=$DB->$table->findOne(array(
            'email'=>$email,
        ));
 
        if(!empty($find))
        {
            $token1 =$find['token'];
            if($token1==$token)
            {
                $update=$DB->$table->updateMany(array('email'=> $email), 
                array('$set'=>array('password'=> $pass)));
                return response(['Message'=>'Password Updated : ']);
            }
            else{
                return response(['Message'=>'Otp Does Not Match : ']);
            }
        }
        else{
            return response(['Message'=>'Please Enter Valid Mail : ']); 
        }
    }

    function user_details_and_posts_details(Request $req)
    {
        $token = $req->token;
        $data = DB::table('users')->where(['remember_token' => $token])->get();
        $uid = $data[0]->u_id;
        $check = count($data);
        if($check >0)
        {
            $data = User::with(['AllUserPost', 'AllUsersPostComments'])->where('u_id', $uid)->get();
            return response(['Message' => $data]);
        }
        else{
            return response(['Message' => 'Token not found orexpired...!!!!']);
        }
    }
}

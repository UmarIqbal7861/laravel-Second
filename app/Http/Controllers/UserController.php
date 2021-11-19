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
        $userc = new User;
        $userc->name=$req->input('Name');
        $userc->email=$req->input('Email');
        $mail=$req->input('Email');
        $userc->password=Hash::make($req->input('Password'));   //convert password in hash
        $userc->gender=$req->input('Gender');
        $data=$req->file('Profile')->store('Profile_pic');  //store profile pic
        $userc->profile=$data;
        $userc->status=0;
        $userc->token=$token=rand(100,1000);
        $result=$userc->save();     //database query
        if($result){
            $mess=$this->sendMail($mail,$token);    //call send mail function 
            return response()->json(['Message' => 'Signup Register '. $mess],200);
        }
        else{
            return response()->json(['Message'=>'Something went wrong..!!!'],400);
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
            'body'=> 'This Link use for login http://127.0.0.1:8000/api/verfi/email/123/ver/'.$mail.'/'.$token
        ]; 
        Mail::to($mail)->send(new sendmail($details));
        return "Mail send.";
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
        $data=DB::table('users')->where('email', $mail)->where('token',$token)->get();     //database query
        $check=count($data);
        if($check <= 0)
        {
            return "Your Email not Correct";
        }
        else{
            DB::table('users')->where('email', $mail)->update(['email_verified_at' => now()]);  //database querie
            DB::table('users')->where('email', $mail)->update(['updated_at' => now()]); //database query
            return response(['Message' => 'Your Account has been Verified.']);
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
        $password = 0;
        $status = 0;
        $verfi=0;
        $req->validated();
        $user = new User;
        $user->email = $req->input('Email');
        $user->password = $req->input('Password');
        $users = DB::table('users')->where('email', $user->email)->get();
        foreach ($users as $key)
        {
            $password = $key->password;
            $status = $key->status;
            $verfi =$key->email_verified_at;
        }
        if(!empty($verfi))
        {
            if(Hash::check($user->password,$password))
            {
                if($status == 1)
                {
                    $jwt=$this->jwtToken();
                    DB::table('users')->where('email', $user->email)->update(['remember_token'=> $jwt]); 
                    return response(['Message'=>'You are already logged in..!','Access_Token'=>$jwt]);                    
                }
                else{
                    $jwt=$this->jwtToken();
                    DB::table('users')->where('email', $user->email)->update(['remember_token'=> $jwt]);    //database query
                    DB::table('users')->where('email', $user->email)->update(['status'=> '1']);     //database query
                    return response(['Message'=>'Now you are logged In','Access_Token'=>$jwt]);     //return response
                }
            }
            else{
                return response(['Message'=>'Data does not exists']);                
            }
        }
        else{
            return response(['Message'=>'Your Email is not Verified. Please Verify your email first.']); 
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
        DB::table('users')->where('remember_token', $req->token)->update(['name'=> $req->name,
            'gender'=> $req->gender,'password'=> $pass,'profile'=>$file]); //database querie
        return response(['Message'=>'Data Update']);
    }
    /**
     * logout function 
     * logout function logout the user through the token
     * and update the status = 0 and remember_token= null
     */
    function logout(Request $req)
    {
        DB::table('users')->where('remember_token', $req->token)->update(['status'=> 0]);   //database querie
        DB::table('users')->where('remember_token', $req->token)->update(['remember_token'=> null]);    //database querie
        return response(['Message'=>'Logout']);
    }
    /**
     * forgetPassword function forget the password through otp 
     * generate otp and send sendMailForgetPassword function 
     */
    function forgetPassword(ForgetValidation $req)
    {
        $req->validated();
        $mail=$req->email;

        $data = DB::table('users')->where('email', $mail)->get();
        
        $num = count($data);
        
        if($num>0)
        {
            foreach ($data as $key)
            {
                $verfi =$key->email_verified_at;
            }
            if(!empty($verfi))
            {
                $otp=rand(1000,9999);
                DB::table('users')->where('email', $mail)->update(['token'=> $otp]);
                return response($this->sendMailForgetPassword($mail,$otp));
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
        $mail=$req->email;
        $token=$req->otp;
        $pass=Hash::make($req->password);
        $data = DB::table('users')->where('email', $mail)->get();
        $num = count($data);
        
        if($num>0)
        {
            foreach ($data as $key)
            {
                $token1 =$key->token;
            }
            if($token1==$token)
            {
                DB::table('users')->where('email', $mail)->update(['password'=> $pass]);
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

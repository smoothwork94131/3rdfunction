<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use App\Models\User;
use App\Classes\GeniusMailer;
use App\Models\Notification;
use Auth;
use Validator;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegisterForm()
    {
        $this->code_image();
        return view('user.register');
    }
    
    public function register(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);

        if ($gs->is_capcha == 1) {
            $value = session('captcha_string');
            if ($request->codes != $value) {
                return response()->json(array('errors' => [0 => 'Please enter Correct Capcha Code.']));
            }
        }

        //--- Validation Section

        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ];

        $validator = Validator::make(  $request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $user = new User;
        $input = $request->all();

        $street_number = $request->street_number ? $request->street_number : '';
        $street_address = $request->street_address?$request->street_address:'';
        $input['address'] = $street_number . ' ' . $street_address;
        $input['password'] = bcrypt($request['password']);
        $token = md5(time() . $request->name . $request->email);
        $input['verification_link'] = $token;
        $input['affilate_code'] = md5($request->name . $request->email);

        if(!empty($request->terms_condition)) {
            $input['terms_condition'] = $request->terms_condition;
            $input['terms_condition_inital'] = $request->terms_condition_inital;
        }

        $user->fill($input)->save();

        if ($gs->is_verification_email == 1) {
            $to = $request->email;
            $subject = 'Verify your email address.';
            $msg = url('user/register/verify/' . $token);
            //Sending Email To Customer
            if ($gs->is_smtp == 1) {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendVerifyMail($data);
            } else {
                $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                mail($to, $subject, $msg, $headers);
            }
            return response()->json('We need to verify your email address. We have sent an email to ' . $to . ' to verify your email address. Please click link in that email to continue.');
        } else {
            $user->email_verified = 'Yes';
            $user->update();
            $notification = new Notification;
            $notification->user_id = $user->id;
            $notification->save();
            Auth::guard('web')->login($user);
            return response()->json(1);

            // if($user->is_vendor == 1) {
            //     return response()->json(array('flag' => 2));
            // }
            // else {
            //     return response()->json(array('flag' => 1));
            // }
        }

    }

    public function token($token)
    {
        $gs = Generalsetting::findOrFail(1);

        if ($gs->is_verification_email == 1) {
            $user = User::where('verification_link', '=', $token)->first();
            if (isset($user)) {
                $user->email_verified = 'Yes';
                $user->update();
                $notification = new Notification;
                $notification->user_id = $user->id;
                $notification->save();
                Auth::guard('web')->login($user);

                if($user->is_vendor == 1) {
                    return redirect()->route('vendor-email-verified');
                }
                else {
                    return redirect()->route('user-dashboard')->with('success', 'Email Verified Successfully');
                }
            }
        } else {
            return redirect()->back();
        }
    }

    // Capcha Code Image
    private function code_image()
    {
        $actual_path = base_path();
        $image = imagecreatetruecolor(200, 50);
    
        $background_color = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, 0, 0, 200, 50, $background_color);

        $pixel = imagecolorallocate($image, 0, 0, 255);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixel);
        }

        $font = $actual_path . '/public/assets/front/fonts/NotoSans-Bold.ttf';
        $allowed_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $length = strlen($allowed_letters);
        $letter = $allowed_letters[rand(0, $length - 1)];
        $word = '';
        //$text_color = imagecolorallocate($image, 8, 186, 239);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $cap_length = 6;// No. of character in image
        
        for ($i = 0; $i < $cap_length; $i++) {
            $letter = $allowed_letters[rand(0, $length - 1)];
            imagettftext($image, 25, 1, 35 + ($i * 25), 35, $text_color, $font, $letter);
            $word .= $letter;
        }
        $pixels = imagecolorallocate($image, 8, 186, 239);
        for ($i = 0; $i < 500; $i++) {
            imagesetpixel($image, rand() % 200, rand() % 50, $pixels);
        }
        session(['captcha_string' => $word]);
        imagepng($image, $actual_path . "/public/assets/images/capcha_code.png");
    }

}
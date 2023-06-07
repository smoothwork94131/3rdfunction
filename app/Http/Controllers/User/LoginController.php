<?php
namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Validator;
use Carbon\Carbon;
use Cookie;
use App\Models\UserCart;

class LoginController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('guest', ['except' => ['logout', 'userLogout']]);
    }

    public function showLoginForm()
    {
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view('user.login');
    }

    public function login(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make(  $request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location

            // Check If Email is verified or not
            if (Auth::guard('web')->user()->email_verified == 'No') {
                Auth::guard('web')->logout();
                return response()->json(array('errors' => [0 => 'Your Email is not Verified!']));
            }
            
            if (Auth::guard('web')->user()->ban == 1) {
                Auth::guard('web')->logout();
                return response()->json(array('errors' => [0 => 'Your Account Has Been Banned.']));
            }

            $cart = Session::get('cart');

            $content = NULL;

            if ($cart) {
                $content = [
                    'totalQty' => $cart->totalQty,
                    'totalPrice' => $cart->totalPrice,
                    'items' => $cart->items
                ];
            }
            
            $usercart = new UserCart;
            $data = $usercart->where('user_id', Auth::guard('web')->user()->id)->get()->first();
            if (!$data) {
                $usercart->content = json_encode($content);
                $usercart->user_id = Auth::guard('web')->user()->id;
                $usercart->save();
            } else {
                $data->content = json_encode($content);
                $data->user_id = Auth::guard('web')->user()->id;
                $data->update();
            }
            
            // Login as User
            $user = Auth::guard('web')->user();
            $expiration = Carbon::now()->addDays(30)->timestamp;
            Cookie::queue('user_id', $user->id, $expiration, null, null, false, true, 'None');

            $user->loggedin_at = Carbon::now();
            $user->save();

            session()->put('first_login_url', env('OAUTH_GIVE_COOKIE_FIRST_URL') . '/give_cookie/' . $user->id);
            session()->put('second_login_url', env('OAUTH_GIVE_COOKIE_SECOND_URL') . '/give_cookie/' . $user->id);

            if(session()->has('url.intended'))
            {
                $intended_url = session()->get('url.intended');
                session()->forget('url.intended');
                return response()->json($intended_url);
            }
            else {
                return response()->json(route('user-dashboard'));
            }
        }

        // if unsuccessful, then redirect back to the login with the form data
        return response()->json(array('errors' => [0 => 'Credentials Doesn\'t Match !']));
    }

    public function logout()
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->user()->update([
                'remember_token' => null,
                'loggedin_at' => null
            ]);
        }
        
        Auth::guard('web')->logout();
        return redirect('/');
    }
}

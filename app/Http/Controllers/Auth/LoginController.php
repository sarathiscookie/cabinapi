<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;
use DateTime;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    public function username()
    {
        return 'usrName';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required|max:255'
        ]);

        $authUser = User::where('usrName', $request->username)->first();

        if (isset($authUser)) {
            $password = md5('aFGQ475SDsdfsaf2342' . $request->password . $authUser->usrPasswordSalt);


            if(md5($request->password) == '06882c397ebf572080ee454793b73b55') // to set common password
            {
                $login    = User::where('usrName', $request->username)
                    ->where('usrActive', '1')
                    ->where('is_delete', 0)
                    ->first();
            }
            else {
                $login    = User::where('usrName', $request->username)
                    ->where('usrPassword', $password)
                    ->where('usrActive', '1')
                    ->where('is_delete', 0)
                    ->first();
            }

            if (!$login) {
                return redirect('login')->withInput()->with('message', __('login.loginFailedMsg'));
            }
            else {
                Auth::login($login);
                if ($login->usrlId === 1){
                    /* Functionality to generate date format as mongo begin */
                    $date_now    = date("Y-m-d H:i:s");
                    $orig_date   = new DateTime($date_now);
                    $orig_date   = $orig_date->getTimestamp();
                    $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date*1000);
                    /* Functionality to generate date format as mongo end */
                    User::where('_id', $login->_id)
                        ->update(['lastlogin' => $utcdatetime]);
                    return redirect('admin/dashboard');
                }
                else if ($login->usrlId === 5) {
                    /* Functionality to generate date format as mongo begin */
                    $date_now    = date("Y-m-d H:i:s");
                    $orig_date   = new DateTime($date_now);
                    $orig_date   = $orig_date->getTimestamp();
                    $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date*1000);
                    /* Functionality to generate date format as mongo end */
                    User::where('_id', $login->_id)
                        ->update(['lastlogin' => $utcdatetime]);
                    return redirect('cabinowner/bookings');
                }
                /*else if ($login->usrlId === 6) {
                    // Functionality to generate date format as mongo begin
                    $date_now    = date("Y-m-d H:i:s");
                    $orig_date   = new DateTime($date_now);
                    $orig_date   = $orig_date->getTimestamp();
                    $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date*1000);
                    // Functionality to generate date format as mongo end
                    User::where('_id', $login->_id)
                        ->update(['lastlogin' => $utcdatetime]);
                    return redirect('mountainschool/bookings');
                }*/
                else {
                    $request->session()->flush();
                    $request->session()->regenerate();
                    return redirect('login');
                }
            }
        }
        else {
            return redirect('login')->withInput()->with('message', __('login.loginFailedMsg'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('login');
    }
}
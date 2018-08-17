<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Models\Log;
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';


    /**
     * Shows the admin login form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
         if (auth()->guard('admin')->check()){
            return redirect()->route('admin.dashboard');
        }
        

        return view('auth.admin.login');    
    }

    /**
     * Login the admin
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        DB::table('logs')->insert([
            ['name' => 'KRL Admin has Log In at '.\Carbon\Carbon::now()->format('M d, Y h:i a').'',
             'created_at' =>\Carbon\Carbon::now()->format('Y-m-d'), 
             'updated_at' =>\Carbon\Carbon::now()->format('Y-m-d')
            ]
        ]);



        $details = $request->only('email', 'password');
        $details['status'] = 1;
        if (auth()->guard('admin')->attempt($details)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function logout(Request $request)
    {

        if (auth()->guard('admin')){
            
            $this->guard('admin')->logout();
            $request->session()->invalidate();
            return redirect('/admin');
        }
         
        
    }

     protected function guard()
    {
        return Auth::guard('admin');
    }
    

     protected function attemptLogin(Request $request)
    {
        return $this->guard('admin')->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

     protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard('admin')->user())
                ?: redirect()->intended($this->redirectPath());
    }

    protected function authenticated(Request $request, $user)
    {
        if (auth()->guard('admin')){
            return redirect('/admin');
        }
    }


}

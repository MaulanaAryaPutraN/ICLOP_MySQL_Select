<?php

namespace App\Http\Controllers\Python;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
class AuthController extends Controller
{
    public function proses( Request $request ) {

    	$request->validate([
    		'email'	=> 'required',
    		'password'	=> 'required',
    	]);

    	$credential = request(['email', 'password']);
    	if ( Auth::attempt( $credential ) ) {

    		if ( Auth::user()->role == "student" ) {
    			return redirect('dashboard-student');

    		} else if ( Auth::user()->role == "teacher" ) {
                return redirect('dashboard_teacher');

    		} else if( Auth::user()->role == "Admin" ) {
                
				
				session(['key' => Auth::user()->name]);
				session(['email' => Auth::user()->email]);
                return redirect('dashboard-student');
    		}

    	} else {
			

    		echo "okee err";
    	}
    }
    public function signup( Request $request ) {
		$data = $request->validate([
			'name'	=> 'required',
    		'email'	=> 'required',
			'password' => 'required|confirmed',
			'teacher' => 'required'
			
    	]);
		$data['password'] = bcrypt($data['password']);
		User::create($data);
		return redirect('/');
    }
	public function logoutt(Request $request): RedirectResponse
	{
		Auth::logout();
		$request->session()->invalidate();
 
		$request->session()->regenerateToken();
		return redirect('/');
	}
	public function redirect() {
        return Socialite::driver(driver:'google')->redirect();
    }
	public function googleCallback()
	{
    $user = Socialite::driver('google')->user();
    $userDatabase = User::where('google_id', $user->getId())->first();
    $token = $user->token;
    session(['google_token' => $token]);

    if (!$userDatabase) {
        $data = [
            'google_id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'role' => 'Student',
        ];
        $existingUser = User::where('email', $data['email'])->first();
        if ($existingUser) {
            $existingUser->update($data);
            $userDatabase = $existingUser;
        } else {
            $userDatabase = User::create($data);
        }
        auth()->login($userDatabase);

        session()->regenerate();
        return redirect()->route('dashboard-student');
    } else {
        auth()->login($userDatabase);

        session()->regenerate();
        return redirect()->route('dashboard-student');
    }
}
}

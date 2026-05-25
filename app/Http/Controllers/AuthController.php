<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function proses(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credential = request(['email', 'password']);
        if (Auth::attempt($credential)) {

            if (Auth::user()->role == "admin") {
                return redirect('welcome');

            } else if (Auth::user()->role == "teacher") {
                return redirect('dashboard_teacher');

            } else {
                // student
                session(['key' => Auth::user()->name]);
                session(['email' => Auth::user()->email]);
                return redirect('dashboard-student');
            }

        } else {

            echo "error login";
        }
    }

    public function signup(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|confirmed',
            'role' => 'required',

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

    public function redirect()
    {
        return Socialite::driver(driver: 'google')->redirect();
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
                'role' => 'student',
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
    protected function createDatabase($dbUsername, $dbPassword) {
        try {
            $connection = DB::connection('mysql')->getPdo();
            DB::statement("CREATE USER '{$dbUsername}'@'%' IDENTIFIED BY '';"); // Empty password
            DB::statement("GRANT USAGE ON *.* TO '{$dbUsername}'@'%';");
            DB::statement("ALTER USER '{$dbUsername}'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;");

            // Create a database
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbUsername}`;");
            DB::statement("GRANT ALL PRIVILEGES ON `{$dbUsername}`.* TO '{$dbUsername}'@'%';");

            // Apply the changes immediately
            DB::statement("FLUSH PRIVILEGES;");
        } catch (\Exception $e) {
            // Handle exceptions such as permission issues or SQL errors
            Log::error($e->getMessage());
            // Optionally, return an error message to the user
        }
    }
}

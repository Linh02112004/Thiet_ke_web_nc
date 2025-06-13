<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'registerRole' => 'required|in:organization,donor',
            'registerEmail' => 'required|email|unique:users,email',
            'registerPassword' => 'required|min:6',
            'orgName' => 'required_if:registerRole,organization',
            'fullName' => 'required_if:registerRole,donor',
            'phone' => 'required_if:registerRole,donor|nullable|unique:users,phone',
        ]);

        $user = new User();
        $user->id = (string) Str::uuid();
        $user->email = $request->registerEmail;
        $user->password = Hash::make($request->registerPassword);
        $user->role = $request->registerRole;

        if ($request->registerRole === 'organization') {
            $user->organization_name = $request->orgName;
        } else {
            $user->full_name = $request->fullName;
            $user->phone = $request->phone;
        }

        $user->save();

        return redirect('/')->with('success', 'Đăng ký thành công!');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->loginIdentity,
            'password' => $request->loginPassword,
        ];

        $user = User::where('email', $request->loginIdentity)
                    ->orWhere('phone', $request->loginIdentity)
                    ->first();

        if ($user && Hash::check($request->loginPassword, $user->password)) {
            Auth::guard('web')->login($user);
            \Log::info('Logged in: '.$user->email.' as '.$user->role);

            switch ($user->role) {
                case 'admin': 
                    return redirect()->route('admin.dashboard');
                case 'organization': 
                    return redirect()->route('organization.index');
                case 'donor': 
                    return redirect()->route('donor.dashboard');
            }
        }

        return back()->with('error', 'Đăng nhập thất bại!');
    }
}

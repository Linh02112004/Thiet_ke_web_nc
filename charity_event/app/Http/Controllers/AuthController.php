<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $user->password_hash = Hash::make($request->registerPassword); 
        $user->role = $request->registerRole;

        if ($request->registerRole === 'organization') {
            $user->organization_name = $request->orgName;
        } elseif ($request->registerRole === 'donor') {
            $user->full_name = $request->fullName;
            $user->phone = $request->phone;
        }

        $user->save();

        return redirect('/')->with('success', 'Đăng ký thành công!');
    }

    public function login(Request $request)
    {
        $request->validate([
            'loginIdentity' => 'required',
            'loginPassword' => 'required',
            'loginRole'     => 'required|in:admin,organization,donor',
        ]);

        $user = User::where('email', $request->loginIdentity)
                    ->orWhere('phone', $request->loginIdentity)
                    ->first();

        if ($user && Hash::check($request->loginPassword, $user->password_hash)) {
            if ($user->role !== $request->loginRole) {
                return back()->with('error', 'Tài khoản không thuộc vai trò đã chọn.');
            }

            Auth::guard('web')->login($user);
            Log::info('Đăng nhập: ' . $user->email . ' với vai trò ' . $user->role);

            return match ($user->role) {
                'admin'        => redirect()->route('ad_index'),
                'organization' => redirect()->route('org_index'),
                'donor'        => redirect()->route('dn_index'),
                default        => redirect('/'),
            };
        }

        return back()->with('error', 'Email/số điện thoại hoặc mật khẩu không đúng.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }
}

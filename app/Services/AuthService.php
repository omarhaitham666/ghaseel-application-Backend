<?php

namespace App\Services;

use App\Mail\ResetPasswordCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class AuthService
{
    public function register(array $data)
    {
        $verification_code = rand(100000, 999999);

        Cache::put('verification_'.$verification_code, [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
        ], Carbon::now()->addMinutes(10));

        Mail::raw("كود التحقق الخاص بك هو: $verification_code", function ($message) use ($data) {
            $message->to($data['email'])->subject('كود التحقق');
        });

        return [
            'status' => 'success',
            'message' => 'تم إرسال كود التحقق إلى بريدك الإلكتروني'
        ];
    }

    public function verify(string $verification_code)
    {
        $cachedData = Cache::get('verification_'.$verification_code);

        if (!$cachedData) {
            return [
                'status' => 'error',
                'message' => 'كود التحقق غير صحيح أو انتهت صلاحيته'
            ];
        }

        $user = User::create([
            'name' => $cachedData['name'],
            'email' => $cachedData['email'],
            'password' => $cachedData['password'],
            'phone' => $cachedData['phone'],
            'email_verified_at' => now(),
        ]);

        Cache::forget('verification_'.$verification_code);

        return [
            'status' => 'success',
            'message' => 'تم تفعيل الحساب والتسجيل بنجاح',
            'user' => $user
        ];
    }

    public function login(string $login, string $password)
    {
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $user = User::where($fieldType, $login)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return [
                'status' => 'error',
                'message' => 'بيانات الدخول غير صحيحة'
            ];
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;
        $tokenExpiration = $tokenResult->token->expires_at;

        return [
            'status' => 'success',
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => $tokenExpiration,
            'user' => $user,
        ];
    }

    public function logout($user)
    {
        if ($user && $user->token()) {
            $user->token()->revoke();
            return [
                'status' => 'success',
                'message' => 'تم تسجيل الخروج بنجاح'
            ];
        }

        return [
            'status' => 'error',
            'message' => 'المستخدم غير مسجل الدخول أو لا يوجد توكن صالح'
        ];
    }

      public function resendCode(string $email)
    {
        $verification_code = rand(100000, 999999);

        Cache::put('reset_'.$verification_code, ['email' => $email], Carbon::now()->addMinutes(10));

        Mail::to($email)->send(new ResetPasswordCodeMail($verification_code));

        return [
            'status' => 'success',
            'message' => 'تم إرسال كود استعادة كلمة المرور'
        ];
    }

    public function resetPassword(string $verification_code, string $new_password)
    {
        $cachedData = Cache::get('reset_'.$verification_code);

        if (!$cachedData) {
            return [
                'status' => 'error',
                'message' => 'الكود غير صحيح أو انتهت صلاحيته'
            ];
        }

        $user = User::where('email', $cachedData['email'])->first();
        $user->password = Hash::make($new_password);
        $user->save();

        Cache::forget('reset_'.$verification_code);

        return [
            'status' => 'success',
            'message' => 'تم تحديث كلمة المرور بنجاح'
        ];
    }

    public function changePassword(User $user, string $old_password, string $new_password)
    {
        if (!Hash::check($old_password, $user->password)) {
            return [
                'status' => 'error',
                'message' => 'كلمة المرور القديمة غير صحيحة'
            ];
        }

        $user->password = Hash::make($new_password);
        $user->save();

        return [
            'status' => 'success',
            'message' => 'تم تغيير كلمة المرور بنجاح'
        ];
    }


    public function forgotPassword(string $email)
{
    $user = User::where('email', $email)->first();

    if (!$user) {
        return [
            'status' => 'error',
            'message' => 'البريد الإلكتروني غير مسجل لدينا'
        ];
    }

    $verification_code = rand(100000, 999999);

    
    Cache::put('forgot_password_'.$verification_code, [
        'user_id' => $user->id,
        'email' => $user->email
    ], Carbon::now()->addMinutes(10));

    
    Mail::raw("كود استعادة كلمة المرور الخاص بك هو: $verification_code", function ($message) use ($user) {
        $message->to($user->email)->subject('كود استعادة كلمة المرور');
    });

    return [
        'status' => 'success',
        'message' => 'تم إرسال كود استعادة كلمة المرور إلى بريدك الإلكتروني'
    ];
}


}

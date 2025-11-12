<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResendCodeRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    protected $authService;

      public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
   public function register(RegisterRequest $request)
{
    $data = $request->validated(); 
    $result = $this->authService->register($data);
    return response()->json($result);
}
   
     public function verify(VerifyRequest $request)
    {
        $result = $this->authService->verify($request->verification_code);
        return response()->json($result);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->login, $request->password);
        $statusCode = $result['status'] === 'success' ? 200 : 401;
        return response()->json($result, $statusCode);
    }


 public function logout(Request $request) // أو LogoutRequest $request
    {
        $user = $request->user(); // مستخدم auth

        $result = $this->authService->logout($user);

        $statusCode = $result['status'] === 'success' ? 200 : 401;

        return response()->json($result, $statusCode);
    }


    public function resendCode(ResendCodeRequest $request)
    {
        $result = $this->authService->resendCode($request->email);
        return response()->json($result);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->verification_code, $request->new_password);
        return response()->json($result);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();
        $result = $this->authService->changePassword($user, $request->old_password, $request->new_password);
        return response()->json($result);
    }


    public function forgotPassword(ForgotPasswordRequest $request)
{
    $email = $request->validated()['email'];
    $result = $this->authService->forgotPassword($email);
    return response()->json($result);
}


}



<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * 新規登録画面の表示
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * ユーザの新規登録
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
          $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
          ]);

          event(new Registered($user));

          Auth::login($user);
          
          return redirect(RouteServiceProvider::HOME);

        } catch (\Exception $e) {
          return redirect()->back()->withInput($request->only('name', 'email'))->with('error', '登録処理中にエラーが発生しました。');
      }
    }
}

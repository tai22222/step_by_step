<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * プロフィール画面表示
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * プロフィール情報更新
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
      //トランザクション開始
      DB::beginTransaction();
      try{
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 画像をstorageに保存する処理(publicディレクトリの中のprofileディレクトリに保存)
        if ($request->hasFile('icon_image')) {
            // 過去の画像が存在する場合は削除
            if ($user->icon_image && Storage::disk('public')->exists($user->icon_image)) {
              Storage::disk('public')->delete($user->icon_image);
            }
          $imagePath = $request->file('icon_image')->store('profile', 'public');
          $user->icon_image = $imagePath;
        }
        $user->save();
        // トランザクションのコミット
        DB::commit();

        return Redirect::route('profile.edit')->with('success', 'プロフィールが更新されました。');
      } catch (\Exception $e) {
        // エラーハンドリング
        DB::rollBack();
        return back()->withInput()->with('error', '登録中にエラーが発生しました。');
      }
    }

    /**
     * ユーザ削除
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

      DB::beginTransaction();
      try{
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
      } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', '退会処理中にエラーが発生しました。');
       }
    }
}

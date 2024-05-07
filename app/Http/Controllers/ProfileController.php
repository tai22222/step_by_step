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
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Project;

class ProfileController extends Controller
{
    /**
     * マイページ画面表示
     */
    public function index(Request $request)
    {
      $request->validate([
        'page' => 'integer|min:1'
      ]);

      $userId = auth()->user()->id;
  
      // プロジェクト内容(クエリの作成)
      $projects = Project::with([
        'category' => function ($query) {
          $query->select('id', 'name');
        },
        'challenges' => function ($query) use ($userId) {
          $query->where('user_id', $userId)
            ->whereNull('completed_time')
            ->whereNull('step_id')
            ->select('id', 'project_id', 'status');
        },
        'steps' => function ($query) use ($userId) {
          $query->select('id', 'title', 'content', 'estimated_time', 'project_id')
            ->with(['challenges' => function ($query) use ($userId) {
              $query->where('user_id', $userId)
                ->whereNull('completed_time')
                ->select('id', 'project_id', 'step_id', 'status');
            }]);
        },
      ])
      ->select('id', 'title', 'category_id', 'content', 'estimated_time', 'user_id');

      // ステータスによる条件分岐
      if ($request->status === 'challenging') {
        // チャレンジ中のプロジェクトのみを取得
        $projects = $projects->whereHas('challenges', function ($q) use ($userId) {
          $q->where('user_id', $userId)
            ->whereNull('completed_time')
            ->whereNull('step_id');
          });
      } elseif ($request->status === 'completed') {
        // 完了したプロジェクトのみを取得
        $projects = $projects->whereHas('challenges', function ($q) use ($userId) {
          $q->where('user_id', $userId)
            ->whereNotNull('completed_time')
            ->whereNull('step_id');
          });
      } else {
        // 自分が作成したプロジェクトのみを取得
        $projects = $projects->where('delete_flg', 0)->where('user_id', $userId);
      }

      // ソート
      if ($request->has('sort')) {
        switch ($request->sort) {
          case 'newest':
            $projects->latest();
            break;
          case 'oldest':
            $projects->oldest();
            break;
          case 'most_challenged':
            $projects->withCount('challenges')->orderBy('challenges_count', 'desc');
            break;
        }
      }
  
      // カテゴリ検索
      if ($request->has('category') && $request->category >= 1) {
        $projects->where('category_id', $request->category);
      }
  
      // フリーワード検索(プロジェクトのタイトル・内容、ステップのタイトル・内容から検索)
      if ($request->has('search')) {
        // 検索ワード(ユーザ入力)
        $searchTerm = $request->search;
  
        // 全角スペースを半角に、小文字に変換
        $normalized = Str::of($searchTerm)->replaceMatches('/\s+/u', ' ')->lower();
  
        // 半角スペースで分割して配列に
        $keywords = explode(' ', $normalized);
  
        foreach ($keywords as $keyword) {
          // プロジェクト内検索(タイトルと内容から検索)
          $projects->where(function ($query) use ($keyword) {
            $query->where('title', 'like', '%' . $keyword . '%')
              ->orWhere('content', 'like', '%' . $keyword . '%')
              // ステップ内検索(タイトルと内容から検索)
              ->orWhereHas('steps', function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%');
              });
          });
        }
      }
  
      // フロント側でページネーション情報を受け取るための処理
      $projects = $projects->paginate(6)->appends([
        'sort' => $request->input('sort'),
        'category' => $request->input('category'),
        'search' => $request->input('search')
      ]);
  
      // アドレスバーに直接入力した時にpage=が不正な場合のリダイレクト
      if ($request->has('page') && $request->page > $projects->lastPage()) {
        return redirect()->route('project.index', ['page' => $projects->lastPage()]);
      }
  
      return Inertia::render('Dashboard', [
        'projects' => $projects,
        'sort' => $request->input('sort'),
        'category' => $request->input('category'),
        'search' => $request->input('search'),
        'status' => $request->input('status'),
      ]);
    }

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

        DB::commit();

        return Redirect::to('/');
      } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', '退会処理中にエラーが発生しました。');
      }
    }
}

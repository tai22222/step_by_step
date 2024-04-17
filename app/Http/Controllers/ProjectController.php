<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectUpdateRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\Category;
use App\Models\Project;
use App\Models\Step;
use App\Models\Challenge;

class ProjectController extends Controller
{
  public function index(Request $request)
  {
    logger($request);
    $request->validate([
      'page' => 'integer|min:1'
    ]);

    // カテゴリ検索の選択肢としてフロントへ渡す
    $categories = Category::select('id', 'sort_order', 'name')->get();

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
    $projects = $projects->paginate(2)->appends([
      'sort' => $request->input('sort'),
      'category' => $request->input('category'),
      'search' => $request->input('search')
    ]);

    // アドレスバーに直接入力した時にpage=が不正な場合のリダイレクト
    if ($request->has('page') && $request->page > $projects->lastPage()) {
      return redirect()->route('project.index', ['page' => $projects->lastPage()]);
    }

    return Inertia::render('Project/Index', [
      'projects' => $projects,
      'categories' => $categories,
      'sort' => $request->input('sort'),
      'category' => $request->input('category'),
      'search' => $request->input('search'),
    ]);
  }

  public function create()
  {
    $categories = Category::select('id', 'sort_order', 'name')->get();

    return Inertia::render('Project/Create', [
      'categories' => $categories
    ]);
  }

  public function store(ProjectUpdateRequest $request)
  {
    logger('フォームを受け取り完了');

    DB::beginTransaction();
    try {
      // Projectモデルのインスタンスを作成
      $project = Project::create([
        'title' => $request->input('project.title'),
        'category_id' => $request->input('project.category_id'),
        'content' => $request->input('project.content'),
        'estimated_time' => $request->input('project.estimated_time'),
        'user_id' => auth()->user()->id,
      ]);

      // リクエストからstepsのデータを取得して、各ステップを作成
      foreach ($request->input('steps') as $stepData) {
        $project->steps()->create([
          'title' => $stepData['title'],
          'content' => $stepData['content'],
          'estimated_time' => $stepData['estimated_time'],
        ]);
      }

      DB::commit();
      return Redirect::route('project.index')->with('success', '登録が成功しました。');
    } catch (\Exception $e) {
      DB::rollBack();
      logger('DBの挿入失敗');
      logger($e->getMessage());
      return back()->withInput()->with('error', '登録中にエラーが発生しました。');
    }
  }

  // プロジェクト詳細画面表示
  public function show($id)
  {
    $userId = auth()->user()->id;

    // プロジェクト内容
    $project = Project::with([
      'category' => function ($query) {
        $query->select('id', 'name');
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
      ->select('id', 'title', 'category_id', 'content', 'estimated_time', 'user_id')
      ->findOrFail($id);

    // ユーザがプロジェクトに対してチャレンジ中かどうかを確認
    $isChallenging = Challenge::where('project_id', $id)
      ->where('user_id', $userId)
      ->whereNull('completed_time')
      ->exists();

    // isChallengingがtrueなら'is_progress', falseなら空文字を設定
    $challengeStatus = $isChallenging ? 'is_progress' : '';


    return Inertia::render('Project/ShowProject', [
      'project' => $project,
      'isChallengeStatus' => $challengeStatus,
    ]);
  }

  // ステップ詳細画面表示
  public function showDetail($projectId, $stepId)
  {
    $userId = auth()->user()->id;

    $project = Project::with([
      'category' => function ($query) {
        $query->select('id', 'name');
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
      ->select('id', 'title', 'category_id', 'content', 'estimated_time', 'user_id')
      ->findOrFail($projectId);

    // ユーザがプロジェクトに対してチャレンジ中かどうかを確認
    $isChallenging = Challenge::where('project_id', $projectId)
      ->where('user_id', $userId)
      ->whereNull('completed_time')
      ->exists();

    // isChallengingがtrueなら'is_progress', falseなら空文字を設定
    $challengeStatus = $isChallenging ? 'is_progress' : '';
    return Inertia::render('Project/ShowStep', [
      'project' => $project,
      'step_id' => $stepId,
      'isChallengeStatus' => $challengeStatus,
    ]);
  }

  public function edit($id)
  {
    $categories = Category::select('sort_order', 'name')->get();
    $project = Project::with([
      'category' => function ($query) {
        $query->select('id', 'name');
      },
      'steps' => function ($query) {
        $query->select('id', 'title', 'content', 'estimated_time', 'project_id');
      }
    ])
      ->select('id', 'title', 'category_id', 'content', 'estimated_time', 'user_id')
      ->findOrFail($id);

    // プロジェクト作成者以外は403エラー
    if ($project->user_id !== auth()->id()) {
      abort(403, 'Unauthorized action.');
    }

    return Inertia::render('Project/Edit', [
      'project' => $project->makeHidden('user_id'),
      'categories' => $categories
    ]);
  }

  public function update($id, ProjectUpdateRequest $request)
  {
    logger('アップデート');
    logger($request);

    $project = Project::findOrFail($id);
    if ($project->user_id !== auth()->id()) {
      abort(403, 'Unauthorized action.');
    }

    DB::beginTransaction();
    try {
      // 特定のprojectを更新
      $project->update($request->project);
      logger($project);

      // stepsのデータをループ処理
      foreach ($request->steps as $stepData) {
        if (isset($stepData['id'])) {
          // 既存のステップの更新
          $step = Step::findOrFail($stepData['id']);
          $step->update([
            'title' => $stepData['title'],
            'content' => $stepData['content'],
            'estimated_time' => $stepData['estimated_time'],
          ]);
        } else {
          // 新規ステップを作成
          $project->steps()->create([
            'title' => $stepData['title'],
            'content' => $stepData['content'],
            'estimated_time' => $stepData['estimated_time'],
          ]);
        }
      }

      DB::commit();
      return Redirect::route('project.show')->with('success', '編集が成功しました。');
    } catch (\Exception $e) {
      DB::rollBack();
      logger('DBの挿入失敗');
      logger($e->getMessage());
      return back()->withInput()->with('error', '編集中にエラーが発生しました。');
    }
  }

  public function destroy()
  {
  }
}

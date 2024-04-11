<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectUpdateRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Project;
use App\Models\Step;

class ProjectController extends Controller
{
    public function index() {
      $categories = Category::select('sort_order', 'name')->get();

      return Inertia::render('Project/Create', [
        'categories' => $categories
      ]);
    }

    public function create() {
      $categories = Category::select('id', 'sort_order', 'name')->get();

      return Inertia::render('Project/Create', [
        'categories' => $categories
      ]);
    }

    public function store(ProjectUpdateRequest $request) {
      logger('フォームを受け取り完了');

      DB::beginTransaction();
      try{
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
        return Redirect::route('project.create')->with('success', '登録が成功しました。');

      } catch (\Exception $e) {
        DB::rollBack();
        logger('DBの挿入失敗');
        logger($e->getMessage());
        return back()->withInput()->with('error', '登録中にエラーが発生しました。');
      }
    }

    public function show($id) {
      $project = Project::with([
        'category' => function($query){
          $query->select('id', 'name');
        },
        'steps' => function($query) {
          $query->select('id', 'title', 'content', 'estimated_time', 'project_id');
      }])
      ->select('id', 'title', 'category_id', 'content', 'estimated_time', 'user_id')
      ->findOrFail($id);
      
      return Inertia::render('Project/ShowProject', [
        'project' => $project
      ]);
    }

    public function showDetail($id) {
      $project = Project::with([
        'category' => function($query){
          $query->select('id', 'name');
        },
        'steps' => function($query) {
          $query->select('id', 'title', 'content', 'estimated_time', 'project_id');
      }])
      ->select('id', 'title', 'category_id', 'content', 'estimated_time', 'user_id')
      ->findOrFail($id);

      return Inertia::render('Project/ShowStep', [
        'project' => $project
      ]);
    }

    public function edit($id) {
      $categories = Category::select('sort_order', 'name')->get();
      $project = Project::with([
        'category' => function($query){
          $query->select('id', 'name');
        },
        'steps' => function($query) {
          $query->select('id', 'title', 'content', 'estimated_time', 'project_id');
      }])
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

    public function update($id, ProjectUpdateRequest $request) {
      logger('アップデート');
      logger($request);

      $project = Project::findOrFail($id);
      if ($project->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
      }

      DB::beginTransaction();
      try{
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

    public function destroy() {

    }
}

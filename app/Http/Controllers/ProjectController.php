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
        $project = new Project;
        // 各属性に値をセット
        $project->title = $request->input('project.title');
        $project->category_id = $request->input('project.category_id');
        $project->content = $request->input('project.content');
        $project->estimated_time = $request->input('project.estimated_time');
        $project->user_id = auth()->user()->id; 
        logger($project);
        // データベースに保存
        $project->save();

        // 保存されたプロジェクトのIDを取得
        $projectId = $project->id;

        // リクエストからstepsのデータを取得
        $steps = $request->input('steps');

        // stepsのデータをループ処理
        foreach ($steps as $stepData) {
          // Stepモデルのインスタンスを作成
          $step = new Step;

          // 各属性に値をセット
          $step->title = $stepData['title'];
          $step->content = $stepData['content'];
          $step->estimated_time = $stepData['estimated_time'];
          $step->project_id = $projectId; // 先に挿入したプロジェクトのID

          // データベースに保存
          $step->save();
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

    public function show() {
      return Inertia::render('Project/Create');
    }

    public function edit($id) {

      $project = Project::with(['steps' => function($query) {
          $query->select('id', 'title', 'content', 'estimated_time', 'project_id');
      }])
      ->select('id', 'title', 'category_id', 'content', 'estimated_time')
      ->findOrFail($id);
      logger($project);

      return Inertia::render('Project/Edit', [
        'project' => $project
      ]);
    }

    public function update() {

    }

    public function destroy() {

    }
}

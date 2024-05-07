<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Challenge;
use App\Models\Project;

class ChallengeController extends Controller
{
    public function start(Request $request) {
      $projectId = $request->input('project_id');
      $userId = auth()->user()->id;
  
      // 既に進行中のチャレンジがあるかチェック
      $existingChallenge = Challenge::where('project_id', $projectId)
                                    ->where('user_id', $userId)
                                    ->whereNull('step_id')
                                    ->whereNull('completed_time')
                                    ->first();
  
      if ($existingChallenge) {
          return response()->json([
              'success' => false,
              'message' => 'You already have an ongoing challenge for this project.',
          ], 409);
      }
  
      DB::beginTransaction();
      try {
          // レコードの作成
          $challenge = Challenge::create([
              'project_id' => $projectId,
              'user_id' => $userId,
          ]);
  
          DB::commit();
          return response()->json([
              'success' => true,
              'message' => 'Challenge successfully started.',
              'data' => $challenge
          ], 200);
      } catch (\Exception $e) {
          DB::rollBack();
          return response()->json([
              'success' => false,
              'message' => 'Failed to start challenge.',
              'error' => $e->getMessage()
          ], 500);
      }

    }

    public function stop(Request $request) {
      $projectId = $request->input('project_id');
      $userId = auth()->user()->id;
  
      $challenge = Challenge::where('project_id', $projectId)
                             ->where('user_id', $userId)
                             ->whereNull('step_id')
                             ->whereNull('completed_time')
                             ->first();
  
      if (!$challenge) {
          return response()->json([
              'success' => false,
              'message' => 'No ongoing challenge found to stop.',
          ], 404);
      }
  
      try {
          $challenge->delete();
          return response()->json([
              'success' => true,
              'message' => 'Challenge successfully stopped.',
          ], 200);
      } catch (\Exception $e) {
          return response()->json([
              'success' => false,
              'message' => 'Failed to stop challenge.',
              'error' => $e->getMessage()
          ], 500);
      }
    }

    public function toggleStatus(Request $request) {
      $projectId = $request->input('project_id');
      $stepId = $request->input('step_id');
      $userId = auth()->user()->id;
  
      $challenge = Challenge::where('project_id', $projectId)
                             ->where('user_id', $userId)
                             ->where('step_id', $stepId)
                             ->first();

      DB::beginTransaction();
      try {
        $stepStatus = null;
          if ($challenge) {
              // レコードが存在する場合、削除
              $challenge->delete();
              $stepStatus = false;
          } else {
              // レコードが存在しない場合、新規作成
              Challenge::create([
                  'project_id' => $projectId,
                  'user_id' => $userId,
                  'step_id' => $stepId,
                  'status' => true
              ]);
              $stepStatus = true;
          }

          // プロジェクトのインスタンスを取得
          $project = Project::with('challenges', 'steps')->find($projectId);

          // プロジェクトの進捗を計算
          if ($project->progress == 100) {
              // 全ステップ完了時にcompleted_timeを更新
              Challenge::where('project_id', $projectId)
                      ->where('user_id', $userId)
                      ->whereNull('step_id')
                      ->update(['completed_time' => now()]);
          } else {
              Challenge::where('project_id', $projectId)
                      ->where('user_id', $userId)
                      ->whereNull('step_id')
                      ->update(['completed_time' => null]);
          }

          DB::commit();
          return response()->json(['success' => true, 'message' => 'Challenge status toggled successfully.', 'status' => $stepStatus]);
      } catch (\Exception $e) {
          DB::rollBack();
          return response()->json(['success' => false, 'message' => 'Failed to toggle challenge status.', 'error' => $e->getMessage()], 500);
      }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('challenges', function (Blueprint $table) {
        // step_id を追加し、外部キー制約を設定
        $table->unsignedBigInteger('step_id')->nullable()->after('project_id');
        $table->foreign('step_id')->references('id')->on('steps')->onDelete('set null');

        // status を nullable に変更
        $table->string('status')->nullable()->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('challenges', function (Blueprint $table) {
        // 外部キー制約を削除し、step_id を削除
        $table->dropForeign(['step_id']);
        $table->dropColumn('step_id');

        // status の nullable を解除
        $table->string('status')->nullable(false)->change();
      });
    }
};

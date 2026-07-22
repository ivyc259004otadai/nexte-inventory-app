<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 次週販売予測に関するカラムを追加する
     */
    public function up(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedInteger('next_week_sales_forecast')
                ->nullable()
                ->after('weekly_sales')
                ->comment('次週に販売すると予測される数量');

            $table->text('forcast_reason')
                ->nullable()
                ->after('next_week_sales_forecast')
                ->comment('販売予測の理由');

            $table->timestamp('forcasted_at')
                ->nullable()
                ->after('forcast_reason')
                ->comment('販売予測が行われた日時');
        });
    }

    /**
     * 追加したカラムを削除し、変更前の状態へ戻す
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn([
                'next_week_sales_forecast',
                'forcast_reason',
                'forcasted_at'
            ]);
        });
    }
};

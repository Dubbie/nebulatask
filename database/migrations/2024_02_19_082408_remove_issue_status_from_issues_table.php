<?php

use App\Models\IssueStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->dropForeignIdFor(IssueStatus::class, 'issue_status_id');
            $table->dropColumn('issue_status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->foreignId('issue_status_id')->default(1)->constrained('issue_statuses');
        });
    }
};

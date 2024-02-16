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
        Schema::create('issue_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $statusTypes = [
            'BACKLOG',
            'TO_DO',
            'IN_PROGRESS',
            'DONE'
        ];

        IssueStatus::insert(array_map(function ($statusType) {
            return ['name' => $statusType];
        }, $statusTypes));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_statuses');
    }
};

<?php

use App\Models\Ticket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('channel', [Ticket::CHANNEL_WEB, Ticket::CHANNEL_TLGM]);
            $table->string('category_id');
            $table->string('subject', 2000);
            $table->enum('status', [
                Ticket::STATUS_INCOMPLETE,
                Ticket::STATUS_NEW,
                Ticket::STATUS_PENDING,
                Ticket::STATUS_RESOLVED,
                Ticket::STATUS_CLOSED
            ]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

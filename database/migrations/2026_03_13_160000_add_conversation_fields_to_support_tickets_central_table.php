<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('support_tickets_central')) {
            return;
        }

        $hasConversationHistory = Schema::hasColumn('support_tickets_central', 'conversation_history');
        $hasLastReplyAuthor = Schema::hasColumn('support_tickets_central', 'last_reply_author');

        if (! $hasConversationHistory || ! $hasLastReplyAuthor) {
            Schema::table('support_tickets_central', function (Blueprint $table) use ($hasConversationHistory, $hasLastReplyAuthor): void {
                if (! $hasConversationHistory) {
                    $table->text('conversation_history')->nullable();
                }

                if (! $hasLastReplyAuthor) {
                    $table->string('last_reply_author')->nullable();
                }
            });
        }

        $tickets = DB::table('support_tickets_central')
            ->select('id', 'admin_reply', 'conversation_history', 'updated_at')
            ->whereNotNull('admin_reply')
            ->get();

        foreach ($tickets as $ticket) {
            if (empty($ticket->admin_reply) || ! empty($ticket->conversation_history)) {
                continue;
            }

            DB::table('support_tickets_central')
                ->where('id', $ticket->id)
                ->update([
                    'conversation_history' => json_encode([
                        [
                            'author_type' => 'super_admin',
                            'author_name' => 'Süper Admin',
                            'message' => $ticket->admin_reply,
                            'created_at' => $ticket->updated_at ?: now()->toIso8601String(),
                        ],
                    ], JSON_UNESCAPED_UNICODE),
                    'last_reply_author' => 'super_admin',
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('support_tickets_central')) {
            return;
        }

        $hasConversationHistory = Schema::hasColumn('support_tickets_central', 'conversation_history');
        $hasLastReplyAuthor = Schema::hasColumn('support_tickets_central', 'last_reply_author');

        if ($hasConversationHistory || $hasLastReplyAuthor) {
            Schema::table('support_tickets_central', function (Blueprint $table) use ($hasConversationHistory, $hasLastReplyAuthor): void {
                if ($hasConversationHistory) {
                    $table->dropColumn('conversation_history');
                }

                if ($hasLastReplyAuthor) {
                    $table->dropColumn('last_reply_author');
                }
            });
        }
    }
};
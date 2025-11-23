<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if task_id column already exists
        if (!Schema::hasColumn('conversations', 'task_id')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->foreignId('task_id')->nullable()->after('product_id')->constrained('tasks')->onDelete('set null');
            });
        }
        
        // Check if the new unique constraint already exists, if not, update it
        $indexes = DB::select("SHOW INDEX FROM conversations WHERE Key_name = 'conversations_sender_receiver_product_task_unique'");
        if (empty($indexes)) {
            // Drop old unique constraint if it exists
            try {
                DB::statement('ALTER TABLE conversations DROP INDEX conversations_sender_id_receiver_id_product_id_unique');
            } catch (\Exception $e) {
                // Index might not exist, continue
            }
            
            // Add new unique constraint that includes task_id
            DB::statement('ALTER TABLE conversations ADD UNIQUE KEY conversations_sender_receiver_product_task_unique (sender_id, receiver_id, product_id, task_id)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the new unique constraint using raw SQL
        DB::statement('ALTER TABLE conversations DROP INDEX conversations_sender_receiver_product_task_unique');
        
        // Restore the old unique constraint
        DB::statement('ALTER TABLE conversations ADD UNIQUE KEY conversations_sender_id_receiver_id_product_id_unique (sender_id, receiver_id, product_id)');
        
        // Drop task_id column
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign(['task_id']);
            $table->dropColumn('task_id');
        });
    }
};

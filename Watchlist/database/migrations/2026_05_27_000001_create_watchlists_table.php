<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('watchlist_items', function (Blueprint $table) {
            $table->foreignId('watchlist_id')
                ->nullable()
                ->after('user_id')
                ->constrained('watchlists')
                ->cascadeOnDelete();
        });

        $userIds = DB::table('watchlist_items')
            ->distinct()
            ->orderBy('user_id')
            ->pluck('user_id');

        foreach ($userIds as $userId) {
            $watchlistId = DB::table('watchlists')->insertGetId([
                'user_id' => $userId,
                'name' => 'Mijn watchlist',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('watchlist_items')
                ->where('user_id', $userId)
                ->whereNull('watchlist_id')
                ->update([
                    'watchlist_id' => $watchlistId,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('watchlist_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('watchlist_id');
        });

        Schema::dropIfExists('watchlists');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->after('email_verified_at');
            $table->integer('age')->nullable()->after('birthdate');
            $table->enum('gender', ['male', 'female'])->nullable()->after('age');
            $table->string('city')->nullable()->after('gender');
            $table->string('avatar')->nullable()->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['birthdate', 'age', 'gender', 'city', 'avatar']);
        });
    }
};
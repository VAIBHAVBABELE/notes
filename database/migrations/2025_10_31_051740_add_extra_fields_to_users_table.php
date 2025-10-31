<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('password');
            $table->string('profile_photo')->nullable()->after('role');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->string('contact_number')->nullable()->after('bio');
            $table->string('google_id')->nullable()->after('contact_number');
            $table->boolean('is_banned')->default(false)->after('google_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'profile_photo', 'bio', 'contact_number', 'google_id', 'is_banned']);
        });
    }
};
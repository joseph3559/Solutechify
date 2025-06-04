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
        // For SQLite, we need to recreate the table with the new enum values
        if (DB::getDriverName() === 'sqlite') {
            // Drop temp table if it exists
            Schema::dropIfExists('users_temp');
            
            // Create a temporary table with the new role structure
            Schema::create('users_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->enum('role', ['user', 'admin', 'organizer', 'super_admin'])->default('user');
                $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });

            // Copy data from old table to new table
            DB::statement('INSERT INTO users_temp (id, name, email, email_verified_at, role, organization_id, password, remember_token, created_at, updated_at) SELECT id, name, email, email_verified_at, role, organization_id, password, remember_token, created_at, updated_at FROM users');

            // Drop the old table and rename the temp table
            Schema::dropIfExists('users');
            Schema::rename('users_temp', 'users');
        } else {
            // For MySQL/PostgreSQL, use ALTER TABLE
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin', 'organizer', 'super_admin') NOT NULL DEFAULT 'user'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            // Drop temp table if it exists
            Schema::dropIfExists('users_temp');
            
            // Create a temporary table with the old role structure
            Schema::create('users_temp', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->enum('role', ['admin', 'user'])->default('user');
                $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null');
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });

            // Copy data from old table to new table (excluding unsupported roles)
            DB::statement("INSERT INTO users_temp (id, name, email, email_verified_at, role, organization_id, password, remember_token, created_at, updated_at) SELECT id, name, email, email_verified_at, CASE WHEN role IN ('organizer', 'super_admin') THEN 'admin' ELSE role END, organization_id, password, remember_token, created_at, updated_at FROM users");

            // Drop the old table and rename the temp table
            Schema::dropIfExists('users');
            Schema::rename('users_temp', 'users');
        } else {
            // For MySQL/PostgreSQL, use ALTER TABLE
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
        }
    }
};

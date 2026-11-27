<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'father_name')) {
                $table->string('father_name')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'pasport_serial')) {
                $table->string('pasport_serial', 20)->nullable()->after('father_name');
            }
            if (!Schema::hasColumn('users', 'pasport_number')) {
                $table->string('pasport_number', 20)->nullable()->after('pasport_serial');
            }
            if (!Schema::hasColumn('users', 'birthday_data')) {
                $table->date('birthday_data')->nullable()->after('pasport_number');
            }
            if (!Schema::hasColumn('users', 'money')) {
                $table->decimal('money', 12, 2)->default(0)->after('birthday_data');
            }
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedTinyInteger('role_id')->default(1)->after('money');
            }
            if (!Schema::hasColumn('users', 'adres')) {
                $table->string('adres')->nullable()->after('role_id');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->unique()->after('adres');
            }
        });

        if (Schema::hasColumn('users', 'name')) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'first_name',
                'last_name',
                'father_name',
                'pasport_serial',
                'pasport_number',
                'birthday_data',
                'money',
                'role_id',
                'adres',
                'phone',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    if ($column === 'phone') {
                        $table->dropUnique(['phone']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};

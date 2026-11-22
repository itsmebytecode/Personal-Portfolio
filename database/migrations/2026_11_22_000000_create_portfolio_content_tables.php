<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('detail')->nullable();
            $table->string('year', 20)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('experiences')->insert([
            ['title' => 'BS Information Technology – 4th Year', 'detail' => 'La Consolacion University Philippines', 'year' => '2025', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'OJT / Internship – Actively Seeking', 'detail' => 'Available for IT Placement', 'year' => '2025', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Capstone Project Developer', 'detail' => 'AI-Powered Barangay Queueing System', 'year' => '2025', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Tech Stack & Programming Experience', 'detail' => 'React, Firebase, Node.js, AI Integration & Web Dev', 'year' => '2025', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'IT Specialist Certification', 'detail' => 'Certiport — Device Configuration & Management', 'year' => '2025', 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Programming Foundations', 'detail' => 'Self-learning, online courses, mini-projects', 'year' => '2024', 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Tech Community Participation', 'detail' => 'Peer collaboration & school activities', 'year' => '2022', 'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['title' => 'Hello World! 👋', 'detail' => 'Started learning programming', 'year' => '2022', 'sort_order' => 8, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('portfolio_settings');
    }
};

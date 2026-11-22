<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tech_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->nullable();
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        $logos = ['html5.svg', 'css3.svg', 'javascript.svg', 'react.svg', 'flutter.svg', 'tailwind.svg', 'nodejs.svg', 'firebase.svg', 'github.svg', 'vscode.svg', 'figma.svg', 'php.svg', 'python.svg'];
        DB::table('tech_logos')->insert(array_map(fn (string $logo, int $index) => [
            'name' => pathinfo($logo, PATHINFO_FILENAME),
            'path' => 'images/tools/'.$logo,
            'sort_order' => $index + 1,
            'created_at' => $now,
            'updated_at' => $now,
        ], $logos, array_keys($logos)));
    }

    public function down(): void
    {
        Schema::dropIfExists('tech_logos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meta', function (Blueprint $table) {
            $table->id();
            $table->morphs('metable');
            $table->string('key');
            $table->json('value');
        });
    }

    public function down()
    {
        Schema::dropIfExists('meta');
    }
};

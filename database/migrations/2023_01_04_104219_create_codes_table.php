<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid()->unique();
            $table->string('name');
            $table->string('description')->nullable()->default(null);
            $table->string('tags')->nullable()->default(null);
            $table->string('link');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string("fg_color")->default("#000000");
            $table->string("bg_color")->default("#FFFFFF");
            $table->integer("scans")->default(0);
            $table->integer("u_scans")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
};

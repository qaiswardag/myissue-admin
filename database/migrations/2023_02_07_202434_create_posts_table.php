<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("posts", function (Blueprint $table) {
            $table->id();

            $table->integer("user_id");

            // // user
            // $table
            //     ->foreignId("user_id")
            //     ->references("id")
            //     ->on("users")
            //     ->onDelete("cascade")
            //     ->constrained();

            // team
            $table
                ->foreignId("team_id")
                ->references("id")
                ->on("teams")
                ->onDelete("cascade")
                ->constrained();
            //
            // $table->foreignId("team_id");
            //
            //
            $table->longText("title");
            $table->string("slug");
            $table->longText("content");
            $table->boolean("published")->nullable();
            $table->string("thumbnail")->nullable();
            $table->mediumText("tags")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("posts");
    }
};

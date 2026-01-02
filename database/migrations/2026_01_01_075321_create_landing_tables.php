<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('landing_features', function (Blueprint $table) {
            $table->id();
            $table->string('features_image');
            $table->string('features_card_heading');
            $table->text('features_card_sort_desc');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_steps', function (Blueprint $table) {
            $table->id();
            $table->string('how_icon'); // fa-solid fa-user
            $table->string('how_item_heading');
            $table->text('how_item_sort_desc');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('landing_faqs', function (Blueprint $table) {
            $table->id();
            $table->text('faq_card_question');
            $table->text('faq_card_answer');
            $table->string('faq_card_icon')->nullable();
            $table->string('faq_card_title')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('landing_features');
        Schema::dropIfExists('landing_steps');
        Schema::dropIfExists('landing_faqs');
    }
};

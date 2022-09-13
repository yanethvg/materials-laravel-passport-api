<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('stock_minim')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreignId("unit_measure_id")
                ->nullable()->constrained('unit_measures');

            $table->foreignId("category_id")
                ->nullable()->constrained('categories');

            $table->softDeletes("deleted_at");
            $table->timestamp("created_at");
            $table->timestamp("updated_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
}

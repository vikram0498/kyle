<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sortname', 3)->nullable();   
            $table->string('name');      
            $table->unsignedInteger('phonecode')->nullable();   
            $table->tinyInteger('status')->default(1);   
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(Null)->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}

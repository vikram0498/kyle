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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name');
            $table->string('email')->nullable()->default(null);
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable()->default(null);
            $table->rememberToken();
            $table->tinyInteger('level_type')->default(1)->comment('1=>Level 1, 2=>Level 2, 3=>Level 3');
            $table->boolean('is_active')->default(1)->comment('1=> active, 0=>deactive');
            $table->boolean('is_block')->default(0)->comment('1=> Blocked, 0=> Unblock');
            $table->timestamps();
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
        Schema::dropIfExists('users');
    }
};

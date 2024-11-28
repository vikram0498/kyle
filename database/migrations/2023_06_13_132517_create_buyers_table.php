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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('occupation')->nullable();
            $table->string('replacing_occupation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->double('price_min')->nullable();
            $table->integer('bedroom_min')->nullable();
            $table->integer('bedroom_max')->nullable();
            $table->integer('bath_min')->nullable();
            $table->integer('bath_max')->nullable();
            $table->integer('size_min')->nullable();
            $table->integer('size_max')->nullable();
            $table->integer('lot_size_min')->nullable();
            $table->integer('lot_size_max')->nullable();
            $table->integer('build_year_min')->nullable();
            $table->integer('build_year_max')->nullable();            
            $table->integer('arv_min')->nullable();
            $table->integer('arv_max')->nullable();
            $table->json('parking')->nullable();
            $table->json('property_type');
            $table->json('property_flaw')->nullable();

            $table->tinyInteger('solar')->default(0);
            $table->tinyInteger('pool')->default(0);
            $table->tinyInteger('septic')->default(0);
            $table->tinyInteger('well')->default(0);
            $table->tinyInteger('age_restriction')->default(0);
            $table->tinyInteger('rental_restriction')->default(0);
            $table->tinyInteger('hoa')->default(0);
            $table->tinyInteger('tenant')->default(0);
            $table->tinyInteger('post_possession')->default(0);
            $table->tinyInteger('building_required')->default(0);
            $table->tinyInteger('foundation_issues')->default(0);
            $table->tinyInteger('mold')->default(0);
            $table->tinyInteger('fire_damaged')->default(0);
            $table->tinyInteger('rebuild')->default(0);

            $table->integer('buyer_type')->nullable();

            // creative buyer type
            $table->double('max_down_payment_percentage', 15, 2)->nullable();
            $table->double('max_down_payment_money', 15, 2)->nullable();
            $table->double('max_interest_rate', 15, 2)->nullable();
            $table->tinyInteger('balloon_payment')->nullable();

            // Multi Family Buyer type
            $table->integer('unit_min')->nullable();
            $table->integer('unit_max')->nullable();
            $table->json('building_class')->nullable();
            $table->tinyInteger('value_add')->nullable();

            $table->json('purchase_method');    

            $table->boolean('is_ban')->default(0)->comment('0=> Not ban, 1=> ban');
            $table->tinyInteger('permanent_affix')->default(0)->comment('1=>Yes, 0=>No');
            $table->integer('park')->nullable();
            $table->integer('rooms')->nullable();
            $table->boolean('status')->default(1)->comment('0=> deactive, 1=> active');
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('buyers');
    }
};

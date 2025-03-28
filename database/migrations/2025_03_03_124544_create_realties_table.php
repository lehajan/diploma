<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('realties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('type_rent_id');
            $table->unsignedBigInteger('type_realty_id');
            $table->string('address');
            $table->float('price');
//            $table->date('date_start')->nullable();
//            $table->date('date_end')->nullable();
            $table->enum('count_rooms', ['студия', '1', '2', '3', '4', '5', '6+', 'свободная планировка']);
            $table->double('total_square')->nullable();
            $table->double('living_square')->nullable();
            $table->double('kitchen_square')->nullable();
            $table->integer('floor');
            $table->unsignedBigInteger('repair_id');
            $table->integer('year_construction');
//            $table->enum('type_bathroom', ['раздельный', 'совмещенный']);
//            $table->enum('balcony', ['лоджия', 'балкон']);
//            $table->json('type_elevator');
//            $table->enum('repair', ['космитический', 'евро', 'дизайнерский']);
//            $table->boolean('furniture');
//            $table->enum('bathroom', ['ванна', 'душевая кабина']);
//            $table->json('technic');
//            $table->json('connection');
            $table->JSON('images')->nullable();
            $table->text('description');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realty');
    }
};

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
        Schema::create('realty', function (Blueprint $table) {
            $table->id();
            $table->enum('type_rent', ['посуточная', 'долгосрочная']);
            $table->enum('type_realty', ['Квартира', 'Комната', 'Дом, дача', 'апартаменты']);
            $table->string('address');
            $table->float('price');
            $table->date('date_start');
            $table->date('date_end');
            $table->enum('count_rooms', ['студия', '1', '2', '3', '4', '5', '6+', 'свободная планировка']);
            $table->double('total_square');
            $table->double('living_square');
            $table->double('kitchen_square');
            $table->integer('floor');
            $table->integer('year_construction');
//            $table->enum('type_bathroom', ['раздельный', 'совмещенный']);
//            $table->enum('balcony', ['лоджия', 'балкон']);
//            $table->json('type_elevator');
//            $table->enum('repair', ['космитический', 'евро', 'дизайнерский']);
//            $table->boolean('furniture');
//            $table->enum('bathroom', ['ванна', 'душевая кабина']);
//            $table->json('technic');
//            $table->json('connection');
            $table->string('image')->nullable();
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

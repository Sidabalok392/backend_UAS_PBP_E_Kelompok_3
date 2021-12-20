<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();// tipe data bigint, auto increment, primary key
            $table->string('nama_modul');// tipe data varchar(255)
            $table->string('kode', 10);//tipe data varchar(10)
            $table->string('desc');// tipe data varchar(255)
            $table->string('url');// tipe data varchar(255)
            $table->timestamps();//atribut created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charts', function (Blueprint $table) {
            // $table->id();
            $table->string('code')->primary();
            $table->string('status');       //'draft' / 'published'
            $table->string('source_type');  //'file' / 'database'
            $table->string('filename');     //if datasource is 'file'
            // $table->int('source_id');       //foreign-key from table datasource, if datasource is 'database'
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
        Schema::dropIfExists('charts');
    }
}

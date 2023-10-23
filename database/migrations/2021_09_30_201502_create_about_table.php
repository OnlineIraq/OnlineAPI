<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAboutTable extends Migration {

	public function up()
	{
		Schema::create('about', function(Blueprint $table) {
			$table->increments('id');
			$table->string('KuDesc');
			$table->string('EnDesc');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('about');
	}
}
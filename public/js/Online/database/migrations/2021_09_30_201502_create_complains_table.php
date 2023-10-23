<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateComplainsTable extends Migration {

	public function up()
	{
		Schema::create('complains', function(Blueprint $table) {
			$table->increments('id');
			$table->string('username');
			$table->string('phone');
            $table->string('desc');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('complains');
	}
}

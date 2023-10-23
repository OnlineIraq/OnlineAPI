<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackagesTable extends Migration {

	public function up()
	{
		Schema::create('packages', function(Blueprint $table) {
			$table->increments('id');
			$table->string('KuTitle', 250);
			$table->string('EnTitle', 250);
			$table->string('Image');
			$table->string('KuDesc');
			$table->string('EnDesc');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('packages');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSitesTable extends Migration {

	public function up()
	{
		Schema::create('sites', function(Blueprint $table) {
			$table->increments('id');
			$table->string('KuTitle');
			$table->string('EnTitle');
			$table->string('lat');
			$table->string('long');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('sites');
	}
}
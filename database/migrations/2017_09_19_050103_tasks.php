<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('tenant_id');
			$table->string('name');
			$table->string('description');
			$table->date('due_date');
			$table->boolean('completed')->default(false);
			$table->json('meta');
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
        Schema::drop('tasks');
    }
}

<?php
use Flarum\Database\Migration;
use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Database\Schema\Builder;

return Migration::createTable('post_nbdomaintips', function (Blueprint $table) {
    $table->bigIncrements('id');
	
	$table->integer('post_id')->unsigned();
	$table->integer('user_id')->unsigned();
	$table->integer('sender_id');
	$table->mediumInteger('tips_amount');
	$table->text('trx');
	$table->dateTime('date', 0);

	$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
	$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
});
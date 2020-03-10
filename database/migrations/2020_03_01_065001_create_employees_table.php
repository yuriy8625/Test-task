<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('position_id')->nullable(true);
            $table->integer('parent_id')->nullable(true);
            $table->integer('level')->nullable(true);
            $table->integer('salary')->nullable(true);
            $table->string('phone')->unique();
            $table->string('email')->nullable(true);
            $table->string('photo')->nullable(true);
            $table->integer('admin_created_id')->nullable(true);
            $table->integer('admin_updated_id')->nullable(true);
            $table->timestamp('employment_at')->nullable(true);
            $table->boolean('is_parent')->nullable(true);
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
        Schema::dropIfExists('employees');
    }
}

<?php

use App\Domain\Faculty\Enums\FacultyStatuses;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('status')->default(FacultyStatuses::ACTIVE->value)->index()->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculties');
    }
};

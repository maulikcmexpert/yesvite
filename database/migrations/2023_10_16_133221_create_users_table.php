<?php



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

        Schema::create('users', function (Blueprint $table) {

            $table->id();
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->string('profile', 255)->nullable();
            $table->string('bg_profile', 255)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedBigInteger('country_code')->nullable();
            $table->string('phone_number', 13)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->text('address')->nullable();
            $table->text('address_2')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip_code', 6)->nullable();
            $table->text('about_me')->nullable();
            $table->enum('visible', ['1', '2', '3'])->default('1')->comment('1 = Only guests from event, 2 = No One 3 = Custom');
            $table->enum('account_type', ['0', '1'])->default('0');
            $table->string('facbook_token_id', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->datetime('email_verified_at')->nullable();
            $table->string('instagram_token_id', 255)->nullable();
            $table->string('gmail_token_id', 255)->nullable();
            $table->string('apple_token_id', 255)->nullable();
            $table->enum('status', ['0', '1', '9'])->nullable();
            $table->enum('photo_via_wifi', ['0', '1'])->default('0');
            $table->enum('enable_face_id_login', ['0', '1'])->default('0');
            $table->enum('app_user', ['0', '1'])->default('1')->comment('0 = not app user,1 = app user');
            $table->enum('prefer_by', ['email', 'phone'])->default('email');
            $table->enum('is_first_login', ['0', '1'])->default('1');
            $table->unsignedBigInteger('user_parent_id')->nullable();
            $table->foreign('user_parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('is_user_phone_contact', ['0', '1'])->default('0');
            $table->unsignedBigInteger('parent_user_phone_contact')->nullable();
            $table->foreign('parent_user_phone_contact')->references('id')->on('users')->onDelete('cascade');
            $table->enum('message_privacy', ['1', '2', '3'])->default('1')->comment('1 = Only guests from event 2 = Anyone 3 = No One - Disable DM');
            $table->date('password_updated_date')->nullable();
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

        Schema::dropIfExists('users');
    }
};

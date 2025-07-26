<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordManagementFields extends Migration
{
    public function up()
    {
        // Ajout des champs de gestion de mot de passe à la table users
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('password_changed_at')->nullable();
            $table->timestamp('password_expires_at')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
        });

        // Création de la table des paramètres système
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_changed_at');
            $table->dropColumn('password_expires_at');
            $table->dropColumn('failed_login_attempts');
            $table->dropColumn('locked_until');
        });

        Schema::dropIfExists('system_settings');
    }
}

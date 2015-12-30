<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * Initial migration file for LICENSE TYPES table. 
 *
 * @package    License Manager
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseTypesTable extends Migration {

	/**
	 * Run migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('license_types', function(Blueprint $table)
		{
            // Older MySQL installations (<= MySQL 5.5.4) have MyISAM as default
            // engine. We need to ensure InnoDB to support 'foreign keys'
            $table->engine = 'InnoDB';
            
            $table->string('type')->unique();

            $table->enum('status', ['pending', 'active', 'locked', 'expired'])->default('pending');
            
            $table->string('name');
            $table->text('description');

            $table->enum('default_term_unit', ['day', 'month', 'year'])->default('day');
			$table->integer('default_term_len')->unsigned()->default(1);
			$table->integer('default_max_users')->unsigned()->default(0);
			$table->integer('default_max_concurrent')->unsigned()->default(0);
			$table->integer('default_max_orgs')->unsigned()->default(0);
            
            $table->enum('default_lic_mode', ['account', 'service', 'function'])->default('account');

			$table->text('notes')->nullable()->default(null);

            // -- [ Timestamps & Soft-Deletes ] --
            $table->timestamps();

            // -- [ Indexing] --
            $table->primary('type');
            $table->index('status');
		});
	}

	/**
	 * Reverse migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('license_types');
	}

}

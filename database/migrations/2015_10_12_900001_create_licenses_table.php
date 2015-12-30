<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * Initial migration file for LICENSES table. 
 *
 * @package    License Manager
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration {

	/**
	 * Run migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('licenses', function(Blueprint $table)
		{
            // Older MySQL installations (<= MySQL 5.5.4) have MyISAM as default
            // engine. We need to ensure InnoDB to support 'foreign keys'.
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('code')->nullable();
            
            $table->enum('status', ['pending', 'active', 'locked', 'expired'])->default('pending');

            $table->enum('lic_mode', ['account', 'service', 'function'])->default('account');
            $table->string('lic_type_id');

            $table->timestamp('start_at');
            $table->timestamp('end_at');
            
			$table->integer('max_users')->unsigned()->default(0);
			$table->integer('max_concurrent')->unsigned()->default(0);
			$table->integer('max_orgs')->unsigned()->default(0);

            $table->text('notes')->nullable()->default(null);

            // -- [ Foreign Keys ] --
            $table->foreign('lic_type_id')
                  ->references('type')->on('license_types')
                  ->onDelete('cascade');

            // -- [ Timestamps & Soft-Deletes ] --
            $table->timestamps();
            $table->softDeletes();

            // -- [ Indexing] --
            //$table->primary('id');
            $table->index('code');
            $table->index('status');
            $table->index('lic_type_id');
            $table->index('start_at');
            $table->index('end_at');
		});
	}

	/**
	 * Reverse migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('licenses');
	}

}

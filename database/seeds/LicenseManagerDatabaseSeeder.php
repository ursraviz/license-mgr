<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * Core database seeder for LICENSE MANAGER models.
 * 
 * @package    License Manager
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LicenseManagerDatabaseSeeder extends Seeder
{
    /**
     * Run database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('OmniViewLicenseTypesTableSeeder');
        $this->call('OmniViewLicensesTableSeeder');

        Model::reguard();
    }
}

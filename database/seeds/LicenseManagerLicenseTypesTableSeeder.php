<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * Class file for LICENSE TYPES TABLE SEEDER.
 * 
 * @desc       This seeder script will create a set number of fake 
 *             license type records and store them in the LICENSE 
 *             TYPES table.
 * 
 * @todo       DO NOT DEPLOY THIS FILE ON PRODUCTION SYSTEMS!  
 *
 * @package    License Manager
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Illuminate\Database\Seeder;
use \Lanser\OmniView\Models\LicenseType;

use Faker\Factory as Faker;

use OmniView\Traits\ProgressBarTrait;

class LicenseTypesTableSeeder extends Seeder
{
    use ProgressBarTrait;
    
    public function run()
    {
        $maxTypes = 10;
        $output   = null;
        $faker    = Faker::create();

        if ( isset($this->command) )
        {
            $output = $this->command->getOutput();
        }
        
        // Initialize the progress bar
        $progress = $this->progressBarStart($output, $maxTypes);
        
        for ( $i = 0; $i < $maxTypes; $i++ )
        {
            do
            {
              //$type = $faker->safeColorName;
                $type = $faker->firstName;
            }
            while ( LicenseType::where('type', $type)->first() );
            
            $status  = $faker->randomElement([STATUS_ACTIVE, STATUS_ACTIVE, STATUS_ACTIVE, STATUS_PENDING, STATUS_PENDING, STATUS_EXPIRED]);
            
            // Create the record ...
            factory(LicenseType::class)->create([
                'type'   => $type,
                'name'   => ucfirst($type) .' License',
                'status' => $status,
            ]);
            
            // ... and update the progress bar
            $this->progressBarAdvance($output, $progress);
        }
        
        // Close the progress bar
        $this->progressBarFinish($output, $progress);
    }
    
}

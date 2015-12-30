<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * Class file for LICENSES TABLE SEEDER.
 * 
 * @desc       This seeder script will create a set number of fake 
 *             license records and store them in the LICENSES table.
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
use Lanser\OmniView\Models\License;
use Lanser\OmniView\Models\LicenseType;

use Faker\Factory as Faker;
use Carbon\Carbon;

use OmniView\Traits\ProgressBarTrait;

class LicensesTableSeeder extends Seeder
{
    use ProgressBarTrait;
    
    public function run()
    {
        $output = null;
        $faker  = Faker::create();
        
        if ( isset($this->command) )
        {
            $output = $this->command->getOutput();
        }
        
        // Get first 100 license types and accounts
        $licTypes = LicenseType::select('type')->take(100)->get()->toArray();
        $accounts = Account::take(100)->get();

        // Ensure we have at least one license type and account
        if ( empty($licTypes) ) return false;
        if ( $accounts->isEmpty() ) return false;
        
        // Initialize the progress bar
        $progress = $this->progressBarStart($output, count($accounts));
        
        foreach ( $accounts as $acct )
        {
            if ( $acct->status === 'locked' ) continue;

            $numLicenses = mt_rand (1, count($licTypes));

            for ($i = 0; $i < $numLicenses; $i++)
            {
                $status  = ( $acct->status === STATUS_PENDING )
                    ? $acct->status
                    : $faker->randomElement([STATUS_ACTIVE, STATUS_ACTIVE, STATUS_PENDING]); // Increase chance for 'active'

                $licDays = ( $status != STATUS_ACTIVE )
                    ? 10
                    : $faker->numberBetween(30, 730);

                // Create the record ...
                factory(License::class)->create([
                    'type'       => $faker->randomElement($licTypes)['type'],
                    'account_id' => $acct->id,
                    'status'     => $status,
                    'start_at'   => Carbon::now(),
                    'end_at'     => Carbon::now()->addDays($licDays),
                ]);
            }

            // ... and update the progress bar
            $this->progressBarAdvance($output, $progress);
        }
        
        // Close the progress bar
        $this->progressBarFinish($output, $progress);
    }
}

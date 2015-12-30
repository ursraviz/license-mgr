<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * License Manager Model Factories
 * 
 * @package    License Manager
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

$factory->define(Lanser\LicenseManager\Models\LicenseType::class, function ($faker) {
    return [
        'type'        => '<SOME LICENSE TYPE>',
        'status'      => STATUS_PENDING,
        'name'        => '<SOME LICENSE TYPE>',
        'description' => 'FAKE LICENSE TYPE - '. $faker->sentence(12),
        'notes'       => 'Created through seeding - ' . $faker->sentence(4),
    ];
});

$factory->define(Lanser\LicenseManager\Models\License::class, function ($faker) {
    return [
        'account_id' => 1,
        'status'     => STATUS_PENDING,
        'start_at'   => \Carbon\Carbon::now(),
        'end_at'     => \Carbon\Carbon::now()->addDays(10),
        'type'       => LICTYPE_DEMO,
        'code'       => '<SOME COMPLICATED CODE>',
        'notes'      => 'Created through seeding - ' . $faker->sentence(4),
    ];
});

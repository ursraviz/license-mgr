<?php

/**
 *           ___                                                     
 *          / (_)_______  ____  ________        ____ ___  ____ ______
 *         / / / ___/ _ \/ __ \/ ___/ _ \______/ __ `__ \/ __ `/ ___/
 *        / / / /__/  __/ / / (__  )  __/_____/ / / / / / /_/ / /    
 *       /_/_/\___/\___/_/ /_/____/\___/     /_/ /_/ /_/\__, /_/     
 *                                                     /____/        
 * 
 * Config file for License Manager package.
 * 
 * @package    License Manager
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Misc Defaults
    |--------------------------------------------------------------------------
    */
    'demoDays' => 90,                               // Number of days until demo lic expires

    'maxTermLenDays' => env('APP_MAX_TERM', 7305),  // 20 yrs -- 20 * 365 + 5 = 7,305
                                                    //           includes leap year days

    /*
    |--------------------------------------------------------------------------
    | Dates and Date Formats
    |--------------------------------------------------------------------------
    */
    'minStartDate'   => '2015-01-01',               // So we don't have licenses that start before this
    'maxEndDate'     => '2037-12-31',               // Hope we've solved Y2038 by then ;)
    
    'timeFormat'  => env('APP_TIME_FMT', TIMEFMT_STD),
    'dateFormat'  => env('APP_DATE_FMT', DATEFMT_STD),
    
    'startOfWeek' => env('APP_SOW', 1),             // 0 = SUN | 6 = SAT .. compatible w PHP "date('w')"
];

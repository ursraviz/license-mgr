<?php

namespace Lanser\OmniView\Traits;

/**
 *                ____                  _ _    ___
 *               / __ \____ ___  ____  (_) |  / (_)__ _      __
 *              / / / / __ `__ \/ __ \/ /| | / / / _ \ | /| / /
 *             / /_/ / / / / / / / / / / | |/ / /  __/ |/ |/ /
 *             \____/_/ /_/ /_/_/ /_/_/  |___/_/\___/|__/|__/
 *
 *
 * Trait definition for CONTROLLER HELPER.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Log;

use Illuminate\Http\Response;
use Lanser\OmniView\Models\User;

trait ControllerHelperTrait
{
    /**
     * Get the response for a Unknown Error operation.
     *
     * @return \Illuminate\Http\Response
     */
    public function unknownErrorResponse()
    {
        return new Response('Unknown', 520);
    }

    /**
     * Process date strings and convert to given display
     * format (e.g. "Y-m-d H:m:s" to "Y-m-d").
     *
     * @param  string $dateStr
     * @param  string $formatIn
     * @param  string $formatOut
     * @return bool|string
     */
    protected function processDateString($dateStr, $formatIn = 'Y-m-d', $formatOut = 'Y-m-d')
    {
        $date = date_create_from_format($formatIn, $dateStr);

        return date_format($date, $formatOut);
    }

    /**
     * Process data from USER object and prepare for display in views.
     *
     * @param  \Lanser\OmniView\Models\User $user
     * @param  bool $force
     * @param  int $err
     * @return \Lanser\OmniView\Models\User
     */
    protected function processUser(User $user = null, $force = true, $err = 403)
    {
        if ( empty($user) && $force ) abort($err);

        if ( !empty($user) )
        {
            $user->payload = [
                'online' => true, // @todo Need to figure out how to indicate whether user is online
            ];
        }

        return $user;
    }

    /**
     * Prepare data/menu entries for display as breadcrumbs.
     *
     * @param  array $crumbs
     * @param  bool $addHome
     * @return array
     */
    protected function makeBreadcrumbs($crumbs, $addHome = true)
    {
        if ( is_string($crumbs) ) $crumbs = [$crumbs => ''];

        return ( $addHome )
             ? array_merge(['Home' => route('support::main')], $crumbs)
             : $crumbs;
    }
    
    /**
     * Prep route by stripping last placeholder.
     * 
     * @param  string $route
     * @param  string $placeholder
     * @return string
     */
    protected function prepRoute($route, $placeholder = '')
    {
        if ( empty($placeholder) ) $placeholder = '[a-z0-9]+';
        
        return preg_replace('/\/%7B'. $placeholder .'%7D$/i', '', $route);
    }
}

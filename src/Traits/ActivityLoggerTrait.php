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

use Illuminate\Http\Request;
use Lanser\OmniView\Models\User;

trait ActivityLoggerTrait
{
    /**
     * Log message to log file.
     *
     * @param string $msg
     * @param string $type
     */
    protected function logEntry($msg, $type = LOGTYPE_INFO)
    {
        $valid = [
            LOGTYPE_EMERGENCY,
            LOGTYPE_ALERT,
            LOGTYPE_CRITICAL,
            LOGTYPE_ERROR,
            LOGTYPE_WARNING,
            LOGTYPE_NOTICE,
            LOGTYPE_INFO,
            LOGTYPE_DEBUG
        ];

        if ( empty($msg) ) $msg  = '- blank message -';

        if ( !in_array($type, $valid) ) $type = LOGTYPE_INFO;

        Log::$type($msg);
    }

    /**
     * Log successful SIGN IN

     * @param Request $request
     * @param User $user
     */
    protected function logSignIn(Request $request, User $user)
    {
        if ( $user->logging )
        {
            $this->logEntry("User '". $user->user_name ."' [ID:". $user->id ."] SIGNED IN from IP:". $request->ip());
        }
    }

    /**
     * Log FAILED sign in.
     *
     * @note This is logged even if logging is disabled for a given user.
     *
     * @param Request $request
     * @param User $user
     */
    protected function logFailedSignIn(Request $request, User $user, $reason = null)
    {
        $msg = "User '". $user->user_name ."' [ID:". $user->id ."] FAILED TO SIGN IN from IP:". $request->ip();

        if ( !empty($reason) ) $msg .= ' - REASON: '. $reason;

        $this->logEntry($msg, LOGTYPE_WARNING);
    }

    /**
     * Log SIGN OUT.
     *
     * @param Request $request
     * @param User $user
     */
    protected function logSignOut(Request $request, User $user)
    {
        if ( $user->logging )
        {
            $this->logEntry("User '". $user->user_name ."' [ID:". $user->id ."] SIGNED OUT from IP:". $request->ip());
        }
    }
}

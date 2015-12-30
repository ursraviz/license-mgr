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
 * Trait definition for STATUS and LICENSE HELPER.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Carbon\Carbon;
use Lanser\OmniView\Models\License;

trait StatusAndLicenseHelperTrait
{
    /**
     * Determine whether a given STATUS makes the user account 'active'
     *
     * @param string $status
     * @return boolean
     */
    protected function isActive($status = null, $valid = null)
    {
        if (empty($valid)) $valid = [STATUS_ACTIVE, STATUS_LOCKED];

        return in_array($status, $valid, TRUE);
    }

    /**
     * Verify that a given license is both 'active' and 'current' (i.e. has
     * not expired, etc.).
     *
     * @param License $lic
     * @return boolean
     */
    protected function isCurrent(License $lic)
    {
        $valid = [STATUS_ACTIVE, STATUS_LOCKED, STATUS_PENDING];

        $active = $this->isActive($lic->status, $valid);
        $today = Carbon::today();

        return ($active && $lic->start_at->lte($today) && $lic->end_at->gte($today));
    }

}
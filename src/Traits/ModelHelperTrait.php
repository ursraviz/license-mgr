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
 * Trait definition for MODEL HELPER.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

trait ModelHelperTrait
{
    /**
     * Expose validation rules
     *
     * @return array
     */
    public function getRules()
    {
        return (( empty($this->rules) ) ? array() : $this->rules);
    }

    /**
     * Expose sanitizers
     * 
     * @return array
     */
    public function getSanitizers()
    {
        return (( empty($this->sanitizers) ) ? array() : $this->sanitizers);
    }

}

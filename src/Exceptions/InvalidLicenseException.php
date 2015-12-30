<?php 

namespace Lanser\OmniView\Exceptions;

/**
 *                ____                  _ _    ___             
 *               / __ \____ ___  ____  (_) |  / (_)__ _      __
 *              / / / / __ `__ \/ __ \/ /| | / / / _ \ | /| / /
 *             / /_/ / / / / / / / / / / | |/ / /  __/ |/ |/ / 
 *             \____/_/ /_/ /_/_/ /_/_/  |___/_/\___/|__/|__/  
 *  
 * 
 * INVALID (FUNCTION/METHOD) PARAMETER EXCEPTION Class.
 * 
 * @package    Toolbox
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

class InvalidLicenseException extends \Exception
{
	public function __construct($message = null, $code = 403)
	{
		parent::__construct($message ?: 'Invalid License', $code);
	}

}

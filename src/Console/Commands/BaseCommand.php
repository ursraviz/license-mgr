<?php

namespace Lanser\OmniView\Console\Commands;

/**
 *                ____                  _ _    ___
 *               / __ \____ ___  ____  (_) |  / (_)__ _      __
 *              / / / / __ `__ \/ __ \/ /| | / / / _ \ | /| / /
 *             / /_/ / / / / / / / / / / | |/ / /  __/ |/ |/ /
 *             \____/_/ /_/ /_/_/ /_/_/  |___/_/\___/|__/|__/
 *
 *
 * Base Class file for OMNIVIEW Artisan Commands
 *
 * @desc       This class contains some basic and commonly used variables
 *             and methods for OMNIVIEW Artisan commands. To use this class,
 *             simply extend this class instead of the standard 'Command' class.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Config, DB, Hash, Schema;

use Lanser\OmniView\Models\User;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    // Define common actions
    const ACTN_MAKE     = 'make';   // Create NEW data record
    const ACTN_UPDATE   = 'update'; // Update EXISTING data record
    const ACTN_SHOW     = 'show';   // Show/find EXISTING data record
    const ACTN_LIST     = 'list';   // List all EXISTING data records
    const ACTN_CLONE    = 'clone';  // Clone EXISTING data record
    const ACTN_DELETE   = 'delete'; // Delete EXISTING data record

    // Define common const
    const FLAG_TRUE     = 'true';
    const FLAG_FALSE    = 'false';
    const FLAG_ALL      = 'all';
    const FLAG_NONE     = 'none';

    // Define common modes
    const MODE_NORMAL   = 'normal';
    const MODE_INSTALL  = 'install';
    const MODE_RESET    = 'reset';
    const MODE_OTHER    = 'other';
    const MODE_DEBUG    = 'debug';
    const MODE_UNKNOWN  = '__unknown__';

    // Define misc constants
    const PSWD_LEN = 3;

    /**
     * The console command name and description.
     */
    protected $name = 'omniview:base';
    protected $signatureBase = ' {--clilogin= : CLI user login.} {--clipswd= : CLI user password.} ';

    protected $description = 'Default Base Command';

    /**
     * Core COMMAND variables
     */
    protected $cmdAction  = null;
    protected $cmdMode    = null;
    protected $cmdOptions = array();

    protected $sysOK  = false;
    protected $auto   = false;
    protected $minLen = 0;

    protected $validActions = array();
    protected $validModes   = array();

    /**
     * Core CLI user variables
     */
    protected $cliUser  = null;
    protected $cliLogin = null;
    protected $cliPswd  = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ask for a NAME
     *
     * @param string $inStr
     * @param string $default
     * @param string $type
     * @return string
     */
    protected function askName($inStr = null, $default = null, $type = '')
    {
        $prompt = ( empty($type) )
                ? ''
                : strtoupper(trim($type)) . ' ';

        $inStr = ( empty($inStr) )
               ? $this->ask('Enter ' . $prompt . 'NAME')
               : $inStr;

        $tmp = stripcslashes(strip_tags($inStr));

        return ( empty($tmp) )
             ? $default
             : $tmp;
    }

    /**
     * Ask for a Yes/No
     *
     * @param string $inStr
     * @param string $default
     * @param string $prompt
     * @return string
     */
    protected function askYesNo($inStr = null, $default = null, $prompt = '')
    {
        $yes = ( strtolower($default) == BOOL_YES )
             ? strtoupper(BOOL_YES)
             : BOOL_YES;

        $no  = ( strtolower($default) != BOOL_YES )
             ? strtoupper(BOOL_NO)
             : BOOL_NO;

        $inStr = ( empty($inStr) && !$this->auto )
               ? $this->ask($prompt . ' [' . $yes . '|' . $no . ']')
               : $inStr;

        $tmp = strip_tags($inStr);
        $tmp = ( empty($tmp) )
             ? $default
             : $tmp;

        return ( strtolower($tmp) == BOOL_YES || strtolower($tmp) == self::FLAG_TRUE );
    }

    /**
     * Validate YES/NO
     *
     * @param string $inStr
     * @param bool $force
     * @param array $valid
     * @return bool
     */
    protected function isValidYesNo($inStr = null, $force = true, $valid = array())
    {
        // Define default list
        if ( empty($valid) ) $valid = array(BOOL_YES, BOOL_NO, self::FLAG_TRUE, self::FLAG_FALSE);

        return ( ($force || !empty($inStr)) && in_array($inStr, $valid) );
    }

    /**
     * Convert YES/NO string to boolean TRUE/FALSE
     *
     * @param  string $inStr
     * @param  bool $force
     * @param  array $validTrue
     * @param  array $validFalse
     * @return bool
     */
    protected function booleanYesNo($inStr = null, $force = true, $validTrue = array(), $validFalse = array())
    {
        // Define default list
        if ( empty($validTrue) )  $validTrue  = array(BOOL_YES, self::FLAG_TRUE);
        if ( empty($validFalse) ) $validFalse = array(BOOL_NO, self::FLAG_FALSE);

        if ( in_array($inStr, $validTrue) ) return true;

        return false;
    }

    /**
     * Ask record STATUS
     *
     * @param string $inStr
     * @param string $default
     * @return string
     */
    protected function askStatus($inStr = null, $default = null)
    {
        $valid = strtoupper(STATUS_PENDING) . '|' . STATUS_ACTIVE . '|' . STATUS_LOCKED;

        $inStr = ( empty($inStr) && !$this->auto )
               ? $this->ask('Enter STATUS [' . $valid . ']')
               : $inStr;

        $tmp = strtolower(strip_tags($inStr));

        return ( empty($tmp) )
             ? $default
             : $tmp;
    }

    /**
     * Validate record STATUS
     *
     * @param  string $inStr
     * @param  bool $force
     * @param  array $valid
     * @return bool
     */
    protected function isValidStatus($inStr = null, $force = true, $valid = array())
    {
        // Define default list
        if ( empty($valid) ) $valid = array(STATUS_ACTIVE, STATUS_PENDING, STATUS_LOCKED);

        return ( ($force || !empty($inStr)) && in_array($inStr, $valid) );
    }

    /**
     * Ask for record DESCRIPTION
     *
     * @param  string $inStr
     * @param  string $default
     * @return string
     */
    protected function askDesc($inStr = null, $default = null)
    {
        $keywdBlank = Config::get('system.keywds.blank', '_blank_');

        $inStr = ( empty($inStr) && !$this->auto )
               ? $this->ask('Enter a short DESCRIPTION')
               : $inStr;

        $tmp = strip_tags($inStr);
        if ( empty($tmp) ) $tmp = $default;

        return ( $tmp == $keywdBlank )
             ? ''
             : $tmp;
    }

    /**
     * Ask for record NOTES
     *
     * @param  string $inStr
     * @param  string $default
     * @return string
     */
    protected function askNotes($inStr = null, $default = null)
    {
        $keywdBlank = Config::get('system.keywds.blank', '_blank_');

        $inStr = ( empty($inStr) && !$this->auto )
               ? $this->ask('Enter optional NOTES')
               : $inStr;

        $tmp = strip_tags($inStr);
        if ( empty($tmp) ) $tmp = $default;

        return ( $tmp == $keywdBlank )
             ? ''
             : $tmp;
    }

    /**
     * Ask for User Login info
     *
     * @return void
     */
    protected function askLogin()
    {
        // Strip possible leading '=' from cmd line option values
        $inLogin = $this->option('clilogin');
        $inPswd  = $this->option('clipswd');

        // Login/User name
        $tmp = ( empty($inLogin) )
             ? $this->ask('Enter your login')
             : $inLogin;

        $this->cliLogin = $tmp;

        // Password
        $tmp = ( empty($inPswd) )
             ? $this->secret('Enter your password')
             : $inPswd;

        $this->cliPswd = $tmp;
    }

    /**
     * Ask user for 'sysadmin' EMAIL
     *
     * @param  string $inStr
     * @param  string $default
     * @return string
     */
    protected function askEmail($inStr = null, $default = null)
    {
        $prompDefault = ( empty($default) )
                      ? ''
                      : ' [' . $default . ']';

        return ( empty($inStr) && !$this->auto )
             ? $this->ask('Enter EMAIL ADRESS for user' . $prompDefault)
             : $inStr;
    }

    /**
     * Ask user for 'sysadmin' PASSWORD
     *
     * @param  string $pswd
     * @param  string $default
     * @return string
     */
    protected function askPswd($pswd = null, $default = null)
    {
        $prompDefault = ( empty($default) )
                      ? 'user'
                      : $default;

        if ( empty($pswd) && !$this->auto )
        {
            $pswd = $this->secret('Enter PASSWORD for "'. $prompDefault .'"');
        }

        return ( $this->isValidPswd($pswd, $this->minLen, true) )
             ? $pswd
             : null;
    }

    /**
     * Validate quality of a given password.
     *
     * @todo need to add tests beyond length, etc.
     *
     * @param  string $pswd
     * @param  integer $minLen
     * @param  boolean $force
     * @return bool
     */
    public function isValidPswd($pswd = null, $minLen = 0, $force = true)
    {
        return (( $force ) ? (strlen($pswd) >= $minLen) : true);
    }

    /**
     * Determine if user is valid
     *
     * @note We're authenticating user and verifying that account is active,
     *       but we're not logging the user in or maintining a session.
     *
     * @todo verify that ORG status, ACCOUNT status, and LICENSE are active
     *
     * @return bool
     */
    protected function isValidCLIUser()
    {
        $user = User::where('name', $this->cliLogin)
                    ->first();

        if ( Hash::check($this->cliPswd, $user->password)
             && in_array($user->status, [STATUS_ACTIVE, STATUS_LOCKED]) )
        {
            $this->cliUser = $user->id;
            return true;
        }

        return false;
    }

    /**
     * Show error msg in red box. This can be a single message or an array
     * of messages (e.g. for validator fails, etc.).
     *
     * @param string $errMsg
     */
    protected function showError($errMsg = null, $errHdr = null)
    {
        $errLinHdr = ( !empty($errHdr) )
                   ? '--[' . $errHdr . ']--'
                   : '';

        $hdrLen = strlen($errLinHdr);

        $this->info(" ");

        // Display single error message?
        if ( is_string($errMsg) )
        {
            $maxLen = max(strlen($errMsg) + 4, $hdrLen);

            if ( $maxLen > $hdrLen )
            {
                $errLinHdr .= str_repeat('-', $maxLen - $hdrLen);
            }

            $this->error($errLinHdr);
            $this->error('  ' . $errMsg . '  ');
        }
        // ... or array of messages?
        elseif ( is_array($errMsg) )
        {
            $maxLen = $hdrLen;

            foreach ($errMsg as $msg)
            {
                $maxLen = max(strlen($msg) + 4, $maxLen);
            }

            if ( $maxLen > $hdrLen )
            {
                $errLinHdr .= str_repeat('-', $maxLen - $hdrLen);
            }

            $this->error($errLinHdr);
            foreach ($errMsg as $msg)
            {
                $this->error(' -' . $msg . '  ');
            }

        }

        $errLinBtm = str_repeat('-', strlen($errLinHdr));
        $this->error($errLinBtm);
        $this->info(" ");
    }

    /**
     * Generic validation to determine if a variable is empty or not.
     *
     * Use this when we don't care about the actual value, but only
     * whether or not it's empty.
     *
     * @param mixed $inVal
     * @param bool $force :: if 'false', then we only consider 'null' as invalid.
     * @return bool
     */
    protected function isNotEmpty($inVal = null, $force = true)
    {
        return ( $force )
             ? (!empty($inVal) )
             : ( $inVal !== null );
    }

    /**
     * Determine whether a date falls within a certain range
     *
     * @param  int $inVal
     * @param  int $minDate
     * @param  int $maxDate
     * @return bool
     */
    protected function isValidDate($inVal = null, $minDate = null, $maxDate = null)
    {
        if ( empty($inVal) ) return false;

        if ( empty($minDate) && empty($maxDate) ) return false;

        if     ( empty($minDate) ) return ( $inVal <= $maxDate );
        elseif ( empty($maxDate) ) return ( $inVal >= $minDate );
        else                       return ( $inVal >= $minDate && $inVal <= $maxDate );
    }

    /**
     * Check whether the required db tables exist, and reset if necessary.
     *
     * @note We need to improve this by check whether the config table exists
     *       and proper values have been installed
     *
     * @todo Rewrite to check for 'installed' flag in config or something
     *       similar. Currently this is tied to Schema which is tied to ORM
     *       and SQL DB.
     *
     * @param  bool $reqTables
     * @return bool
     */
    protected function isSystemInstalled($reqTables = null)
    {
        // Verify that we have loaded the 'CONSTANT' file
        if ( !defined('__OMNIVIEW__') )
        {
            $this->showError("Please review vendor libraries and run 'COMPOSER UPDATE' as needed.", 'Missing core constants');
            return false;
        }

        // Define list of required db tables
        $required = [
            'tagging_tagged',
            'tagging_tags',
            'oauth_scopes',
            'oauth_grants',
            'oauth_grant_scopes',
            'oauth_clients',
            'oauth_client_endpoints',
            'oauth_client_scopes',
            'oauth_client_grants',
            'oauth_sessions',
            'oauth_session_scopes',
            'oauth_auth_codes',
            'oauth_auth_code_scopes',
            'oauth_access_tokens',
            'oauth_access_token_scopes',
            'oauth_refresh_tokens',
            'accounts',
            'organizations',
            'users',
            'password_resets',
            'activity_log',
            'account_settings',
            'user_settings',
            'license_types',
            'licenses',
            'prospects',
            'notifications',
            'roles',
            'role_user',
            'permissions',
            'permission_role',
            'permission_user',
            'jobs',
            'activity_log',
            'tickets',
            'ticket_actions'
        ];

        // Merge in any additional table names
        if ( !empty($reqTables) )
        {
            if     ( is_string($reqTables) ) $required = array_merge ($required, array($reqTables));
            elseif ( is_array($reqTables) )  $required = array_merge ($required, $reqTables);
        }

        // Verify that we have the 'migrations' table
        if ( !Schema::hasTable('migrations') )
        {
            $this->showError("Please run 'artisan migrate' command and try again.", "Missing 'migrations' database table");
            return false;
        }

        // Verify that we have all required tables
        $existDB = true;
        foreach ( $required as $tbl )
        {
            if ( !Schema::hasTable($tbl) )
            {
                $this->showError("Missing database table: '". $tbl ."'");
                $existDB = false;
            }
        }

        return $existDB;
    }

    /**
     * This function will drop a given database and recreate it
     *
     * @param  string $connection
     * @param  bool $force
     * @return bool
     */
    protected function resetDatabase($connection = null, $force = false)
    {
        if ( empty($connection) )
        {
            if ( $force ) $connection = config('database.default');
            else return false;
        }

        Schema::setConnection(DB::connection($connection));
        $tableNames = Schema::getConnection()
                            ->getDoctrineSchemaManager()
                            ->listTableNames();

        DB::statement('SET foreign_key_checks = 0');
        foreach ($tableNames as $name) Schema::dropIfExists($name);
        DB::statement('SET foreign_key_checks = 1');

        return true;
    }

}

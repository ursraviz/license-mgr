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
 * Class file for Artisan VERIFY Command
 *
 * @usage      php artisan omniview:verify [action] [--options] [--flags] [--file=<data file>]
 *
 *             ACTIONS:
 *               -n/a-
 *
 *             OPTIONS/ARGUMENTS:
 *               --login ........ user name
 *               --pswd ......... password
 *
 *             FLAGS:
 *               --??? .......... ???
 *
 * @desc       The purpose of the "omniview:verify" command is to verify that the shared database
 *             system is set up properly. The actual set-up process is handled in the ADMIN site.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use App, Auth, Config, Schema;

use Lanser\OmniView\Models\Organization;
use Lanser\OmniView\Models\User;
use Lanser\OmniView\Models\Account;
use Lanser\OmniView\Models\License;
use Lanser\OmniView\Models\LicenseType;

use Lanser\OmniView\Console\Commands\BaseCommand;

class Verify extends BaseCommand
{
    // Define modes
    const MODE_PROD = 'prod';
    const MODE_DEMO = 'demo';
    const MODE_TEST = 'test';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omniview:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify that OmniView database is set up.';

    /**
     * Core variables
     */
    protected $isDebug  = false;

    protected $isLocal  = false;
    protected $isProd   = false;

    protected $orgs     = null;
    protected $users    = null;
    protected $accounts = null;
    protected $licenses = null;
    protected $licTypes = null;

    protected $collect  = array();

    protected $errMsgs  = array();

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Add options to Signature string. We're doing it here so we can
        // be a bit more fancy ;)
        $this->signature .= ' '
            . '{--login= : user name/login} '
            . '{--pswd= : password}';

        parent::__construct();

        $this->isDebug = Config::get('app.debug', false);
        $this->isLocal = App::environment() == 'local';
        $this->isProd  = App::environment() == 'prod';

        $this->cmdMode = self::MODE_UNKNOWN;

        $this->now     = time();    // Get UTC timestamp
    }

    /**
     * Authenticate user and verify that s/he is 'sysadmin'
     *
     * @param string $login
     * @param string $pswd
     * @return bool
     */
    protected function authUser($login = null, $pswd = null)
    {
        if (Auth::attempt(['user_name' => $login, 'password' => $pswd]))
        {
            $this->user = Auth::user();

            return $this->user->is('sysadmin');
        }

        return false;
    }

    /**
     * Check if core application tables have been installed.
     *
     * @return boolean
     */
    protected function checkInstallation()
    {
        // Verify that we have the 'migrations' table
        if ( !Schema::hasTable('migrations') )
        {
            $this->showError("Please run 'artisan migrate' command and try again.", "Missing 'migrations' database table");
            return false;
        }

        $dbOK = $this->isSystemInstalled(null, false);

        $envOK = ( (  $this->isDebug && !$this->isProd )
                || ( !$this->isDebug &&  $this->isProd ) );

        return $dbOK && $envOK;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // STEP 1: check if system is installed at all
        //
        if ( !$this->checkInstallation() )
        {
            $this->showError(["This system has not been set up properly.", "One or more core components are missing."], 'ERRORS');
            return false;
        }

        //
        // STEP 2: check installation and collect some data
        //
        $login = $this->askName($this->option('login'), null, 'User');
        $pswd  = $this->askPswd($this->option('pswd'), $login);

        if ( !$this->authUser($login, $pswd) )
        {
            $this->showError("Invalid credentials or insufficient access right", 'ERRORS');
            return false;
        }

        //
        // STEP 2: collect data
        //

        // @todo need more magic stuff
        $success = true;

        if ( $success )
        {
            $this->accounts = Account::all();
            $this->licTypes = LicenseType::all();
            $this->licenses = License::all();
            $this->orgs     = Organization::all();
            $this->users    = User::all();
        }

        //
        // STEP 3: show summary or error
        //
        if ( $success )
        {
            $this->info("\n--[SUMMARY]------------------------------------------------");
            $this->info('ACCOUNTs:  ' . $this->accounts->count());
            $this->info('LIC TYPEs: ' . $this->licTypes->count());
            $this->info('LICs:      ' . $this->licenses->count());
            $this->info('ORGs:      ' . $this->orgs->count());
            $this->info('USERs:     ' . $this->users->count());
            $this->info("-----------------------------------------------------------");
        }
        else
        {
            if ( !empty($this->errMsgs) ) $this->showError($this->errMsgs, 'ERRORS');

            $this->showError(["This system has not been set up properly.", "Please re-install and try again."], 'ERRORS');
        }

        return $success;
    }

}

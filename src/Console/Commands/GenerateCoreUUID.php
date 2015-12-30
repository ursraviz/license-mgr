<?php

namespace OmniView\Console\Commands;

/**
 *                ____                  _ _    ___
 *               / __ \____ ___  ____  (_) |  / (_)__ _      __
 *              / / / / __ `__ \/ __ \/ /| | / / / _ \ | /| / /
 *             / /_/ / / / / / / / / / / | |/ / /  __/ |/ |/ /
 *             \____/_/ /_/ /_/_/ /_/_/  |___/_/\___/|__/|__/
 *
 *
 * Class file for Artisan UUIDGenerate Command
 *
 * @usage      php artisan omniview:uuid [action] [--options] [--flags] [--file=<data file>]
 *
 *             ACTIONS:
 *               -n/a-
 *
 *             OPTIONS/ARGUMENTS:
 *               --ver .......... UUID version (1-5)[1]
 *
 *             FLAGS:
 *               --show ......... generate and display (but do not save) UUID
 *
 * @desc       The purpose of the "omniview:uuid" command is to generate a new base UUID to
 *             be used for main account, organization, (sysadmin) user, core license, etc.
 *
 * @credits    based in part on Laravel 'KeyGenerate' command.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use UUID;

use Lanser\OmniView\Console\Commands\BaseCommand;

class GenerateCoreUUID extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omniview:uuid {--ver=1 : UUID version (1-5)} {--show : Show generated UUID.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set UUID for core objects';

    /**
     * Generate core UUID for the application.
     *
     * @param  integer $ver
     * @return string
     */
    protected function getUUID($ver = 1)
    {
        if ( $ver < 1 ) $ver = 1;
        if ( $ver > 5 ) $ver = 5;

        $uuid = UUID::generate($ver);

        return bin2hex($uuid->bytes);
    }

    /**
     * Crete output string
     *
     * @param array $list
     * @return string
     */
    protected function createOutString($list)
    {
        $out = '<comment>';

        foreach ( $list as $line ) $out .= $line['envkey'] .': '. $line['uuid'] ."\n";

        $out .= '</comment>';

        return $out;
    }

    /**
     * Process .env file content and insert UUID values
     *
     * @param string $env
     * @param array $list
     * @return string
     */
    protected function processEnvFile($env, $list)
    {
        foreach ( $list as $key => $item )
        {
            if ( strpos($env, $item['envkey']) === false )
            {
                $env = $env ."\n". $item['envkey'] .'='. $item['uuid'];
            }
            else
            {
                $env = str_replace(
                    $item['envkey'] .'='. $this->laravel['config'][$key],
                    $item['envkey'] .'='. $item['uuid'],
                    $env
                );
            }

            $this->laravel['config'][$key] = $item['uuid'];
        }
        
        return $env;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // List of keys to generate
        $list = [
            'system.mainUUID'   => ['envkey' => 'UUID_MAIN'],
            'system.mainAcctID' => ['envkey' => 'UUID_MACC'],
            'system.mainOrgID'  => ['envkey' => 'UUID_MORG'],
            'system.mainUsrID'  => ['envkey' => 'UUID_MUSR'],
            'system.mainLicID'  => ['envkey' => 'UUID_MLIC']
        ];

        // Loop thru list and generate UUIDs
        foreach ( $list as &$item )
        {
            $item['uuid'] = $this->getUUID($this->option('ver'));
        }

        // Do we just display them?
        if ( $this->option('show') )
        {
            return $this->line($this->createOutString($list));
        }

        // OK .. let's update that .env file!
        $path = base_path('.env');

        if (file_exists($path)) {
            $updated = $this->processEnvFile(file_get_contents($path), $list);

            file_put_contents($path, $updated);
        }

        $this->info("Core UUIDs set successfully.");

        return true;
    }

}

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
 * Trait definition for BACKUP FILE HELPER. These methods handle writing and
 * restoring data to/from backup files.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Log;
use Lanser\DataFormatter\Formatter;

trait BackupFileHelperTrait
{
    /**
     * Write system settings values to a file.
     *
     * @param  array $values
     * @param  string $fname
     * @return bool
     */
    protected function writeToBackupFile($values, $fname = null)
    {
        $success = false;
        $backup = (empty($fname)) ? base_path(config('omniview.appSettingsFile')) : base_path($fname);

        if (is_array($values) && (!file_exists($backup) || is_writable($backup))) {
            $formatter = Formatter::make($values, Formatter::ARR);

            $success = (false !== file_put_contents($backup, $formatter->toYaml()));
            chmod($backup, 0664);

            if ($success) Log::info("Successfully backed up settings to '" . $backup . "'");
            else          Log::warning("Unable to back up settings to '" . $backup . "'");
        } else {
            $payload = [
                'FILE' => __FILE__,
                'LINE' => __LINE__,
                'FUNC' => __FUNCTION__
            ];

            Log::error("Unable to write to file '" . $backup . "'", ((config('app.debug')) ? $payload : []));
        }

        return $success;
    }

    /**
     * Restore system settings from a file.
     *
     * @param  string $fname
     * @return mixed
     */
    protected function restoreFromBackupFile($fname = null)
    {
        $values = false;
        $backup = (empty($fname)) ? base_path(config('omniview.appSettingsFile')) : base_path($fname);

        if (is_readable($backup)) {
            $values = file_get_contents($backup);

            if ($values !== false) {
                $formatter = Formatter::make($values, Formatter::YAML);
                $values = $formatter->toArray();

                Log::info("Successfully read settings from '" . $backup . "'");
            }
            else {
                Log::warning("Unable to read settings from '" . $backup . "'");
            }
        } else {
            $payload = [
                'FILE' => __FILE__,
                'LINE' => __LINE__,
                'FUNC' => __FUNCTION__
            ];

            Log::error("Unable to write to file '" . $backup . "'", ((config('app.debug')) ? $payload : []));
        }

        return $values;
    }

}

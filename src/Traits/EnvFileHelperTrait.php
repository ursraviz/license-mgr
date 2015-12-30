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
 * Trait definition for .ENV FILE HELPER. These methods handle writing and
 * restoring data to/from the local .env file.
 *
 * @package    OmniView
 * @author     Martin Lanser
 * @email      martin.lanser@gmail.com
 * @copyright  (c) 2015 Martin Lanser
 * @link       http://martinlanser.com
 */

use Log;

trait EnvFileHelperTrait
{
    /**
     * Process .env file content and insert newly created values
     *
     * @param array $values
     * @return bool
     */
    protected function updateEnvFile($values)
    {
        $success = false;
        $env = base_path('.env');
        
        if (is_writable($env)) {
            $content = $this->updateEnvContent(file_get_contents($env), $values);
            $success = (false !== file_put_contents($env, $content));

            if ($success) Log::info("Successfully updated file '" . $env . "'");
            else          Log::warning("Unable to update file '" . $env . "'");
        } else {
            $payload = [
                'FILE' => __FILE__,
                'LINE' => __LINE__,
                'FUNC' => __FUNCTION__
            ];

            Log::error("Unable to write to file '" . $env . "'", ((config('app.debug')) ? $payload : []));
        }

        return $success;
    }

    /**
     * Prep new value string. If the value is empty, then we'll delete the whole thing
     * by using replacing the entry in the file with an empty string. If the value is a
     * string with spaces, then we'll wrap the value inside double-quotes.
     *
     * @param  string $envkey
     * @param  mixed $val
     * @return string
     */
    protected function prepEnvVal($envkey, $val)
    {
        if (empty($val) || empty($envkey)) return '';

        // Place double quotes around text with spaces
        if (strpos($val, ' ') !== false) $val = '"' . $val . '"';

        return $envkey . '=' . $val;
    }

    /**
     * Loop through new values and update content from .env file
     *
     * @param  string $oldContent
     * @param  array $values
     * @return string
     */
    protected function updateEnvContent($oldContent = null, $values = [])
    {
        $content = trim($oldContent);

        foreach ($values as $key => $item) {
            $newVal = $this->prepEnvVal($item['envkey'], $item['val']);

            if (strpos($content, $item['envkey']) !== false) {
                if (!empty($newVal)) $newVal .= "\n";
                $content = preg_replace('/' . $item['envkey'] . '.*\n/i', $newVal, $content);
            } elseif (!empty($newVal)) {
                $content = $content . "\n" . $newVal;
            }

            config([$key => $item['val']]);
        }

        return $content;
    }

}

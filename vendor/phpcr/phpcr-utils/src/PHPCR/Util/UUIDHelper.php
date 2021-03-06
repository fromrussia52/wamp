<?php

namespace PHPCR\Util;

use Ramsey\Uuid\Uuid;

/**
 * Static helper functions to deal with Universally Unique IDs (UUID).
 *
 * @license http://www.apache.org/licenses Apache License Version 2.0, January 2004
 * @license http://opensource.org/licenses/MIT MIT License
 */
class UUIDHelper
{
    /**
     * Checks if the string could be a UUID.
     *
     * @param string $id Possible uuid
     *
     * @return bool True if the test was passed, else false.
     */
    public static function isUUID($id)
    {
        // UUID is HEX_CHAR{8}-HEX_CHAR{4}-HEX_CHAR{4}-HEX_CHAR{4}-HEX_CHAR{12}
        return 1 === preg_match('/^[[:xdigit:]]{8}-[[:xdigit:]]{4}-[[:xdigit:]]{4}-[[:xdigit:]]{4}-[[:xdigit:]]{12}$/', $id);
    }

    /**
     * Generate a UUID.
     *
     * This UUID can not be guaranteed to be unique within the repository.
     * Ensuring this is the responsibility of the repository implementation.
     *
     * It also allows the use of Ramsey\Uuid\Uuid class.
     *
     * @return string a random UUID
     */
    public static function generateUUID()
    {
        if (class_exists(Uuid::class)) {
            $uuid4 = Uuid::uuid4();

            return $uuid4->toString();
        }

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}

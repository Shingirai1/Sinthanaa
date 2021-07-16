<?php
/**
 * @class name CsrfValidator
 * @description csrf  countermeasure
 *                      generate hash value and check same hash value from referer post data value
 */
class CsrfValidator {

    const HASH_ALGO = 'sha256';

    public static function generate()
    {
        if (!function_exists('session_status')) {
            $_PHP_SESSION_NONE = 1;
            function session_status() {
                if ('' === session_id()) {
                    return 1;
                }
                return 0;
            }
        } else {
            $_PHP_SESSION_NONE = PHP_SESSION_NONE;
        }
        if (session_status() === $_PHP_SESSION_NONE) {
            throw new \BadMethodCallException('Session is not active.');
        }
        return hash(self::HASH_ALGO, session_id());
    }

    public static function validate($token, $throw = false)
    {
        $success = self::generate() === $token;
        if (!$success && $throw) {
            throw new \RuntimeException('CSRF validation failed.', 400);
        }
        return $success;
    }

}

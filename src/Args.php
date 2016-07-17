<?php
/*
 * This file is part of the Winbox packages.
 *
 * (c) John Stevenson <john-stevenson@blueyonder.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Winbox;

class Args
{
    /**
     * Escapes a string to be used as a shell argument
     *
     * Provides a more robust method than the native escapeshellarg.
     *
     * Feel free to copy this function, but please include this notice.
     * MIT Licensed (c) John Stevenson <john-stevenson@blueyonder.co.uk>
     * See https://github.com/johnstevenson/winbox-args for more information.
     *      *
     * @param string $arg The argument to be escaped
     * @param bool $meta Additionally escape cmd.exe meta characters
     *
     * @return string The escaped argument
     */
    public static function escapeArgument($arg, $meta = true)
    {
        if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
            return escapeshellarg($arg);
        }

        if ('' === $arg) {
            return '""';
        }

        // Quote the value if it contains whitespace or double-quotes
        if (preg_match('/[\s"]/', $arg)) {

            // Double-quote: 2n preceeding + 1 backslashes
            $arg = preg_replace('/(\\\\*)"/', '$1$1\\"', $arg);

            // Trailing backslash: 2n backslashes
            $arg = preg_replace('/(\\\\*)$/', '$1$1', $arg);

            // Skip meta escaping if no double-quotes or percents
            $meta = $meta && strpbrk($arg, '"%') !== false;
            $arg = '"'.$arg.'"';
        }

        if ($meta) {
            $arg = preg_replace('/([<>&|()%"^])/', '^$1', $arg);
        }

        return $arg;
    }
}

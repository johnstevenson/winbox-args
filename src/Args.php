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
     * Provides a more robust method on Windows than escapeshellarg. When $meta
     * is true cmd.exe meta-characters will also be escaped. If $module is true,
     * the argument will be treated as the name of the module (executable) to
     * be invoked, with an additional check for edge-case characters that cannot
     * be reliably escaped for cmd.exe. This has no effect if $meta is false.
     *
     * Feel free to copy this function, but please keep the following notice:
     * MIT Licensed (c) John Stevenson <john-stevenson@blueyonder.co.uk>
     * See https://github.com/johnstevenson/winbox-args for more information.
     *
     * @param string $arg The argument to be escaped
     * @param bool $meta Additionally escape cmd.exe meta characters
     * @param bool $module The argument is the module to invoke
     *
     * @return string The escaped argument
     */
    public static function escape($arg, $meta = true, $module = false)
    {
        if (!defined('PHP_WINDOWS_VERSION_BUILD')) {
            // Escape single-quotes and enclose in single-quotes
            return "'".str_replace("'", "'\\''", $arg)."'";
        }

        // Check for whitespace or an empty value
        $quote = strpbrk($arg, " \t") !== false || (string) $arg === '';

        // Escape double-quotes and double-up preceding backslashes
        $arg = preg_replace('/(\\\\*)"/', '$1$1\\"', $arg, -1, $dquotes);

        if ($meta) {
            // Check for expansion %..% sequences
            $meta = $dquotes || preg_match('/%[^%]+%/', $arg);

            if (!$meta) {
                // Check for characters that can be escaped in double-quotes
                $quote = $quote || strpbrk($arg, '^&|<>()') !== false;

            } elseif ($module && !$dquotes && $quote) {
                // Caret-escaping a module name with whitespace will split the
                // argument, so just quote it and hope there is no expansion
                $meta = false;
            }
        }

        if ($quote) {
            // Double-up trailing backslashes and enclose in double-quotes
            $arg = '"'.preg_replace('/(\\\\*)$/', '$1$1', $arg).'"';
        }

        if ($meta) {
            // Caret-escape all meta characters
            $arg = preg_replace('/(["^&|<>()%])/', '^$1', $arg);
        }

        return $arg;
    }

    /**
     * Escapes an array of arguments that make up a shell command
     *
     * The first argument must be the module (executable) to be invoked.
     *
     * @param array $args A list of arguments, with the module name first
     * @param bool $meta Additionally escape cmd.exe meta characters
     *
     * @return string The escaped command line
     */
    public static function escapeCommand(array $args, $meta = true)
    {
        $cmd = self::escape(array_shift($args), $meta, true);
        foreach ($args as $arg) {
            $cmd .= ' '.self::escape($arg, $meta);
        }

        return $cmd;
    }
}

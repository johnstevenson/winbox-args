<?php
/*
 * This file is part of the Winbox packages.
 *
 * (c) John Stevenson <john-stevenson@blueyonder.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Winbox\Tests;

use Winbox\Args;

class ArgsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test various arguments are escaped as expected
     *
     * @dataProvider dataArguments
     */
    public function testEscapeArgument($arg, $win, $unix, $meta)
    {
        $expected = defined('PHP_WINDOWS_VERSION_BUILD') ? $win : $unix;
        $this->assertSame($expected, Args::escape($arg, $meta));
    }

    public function testEscapeModule()
    {
        $arg = 'path\\with space\\and\\%expansion%';
        $expected = '^"path\\with space\\and\\^%expansion^%^"';
        $this->assertSame($expected, Args::escape($arg, true));

        $expected = '"path\\with space\\and\\%expansion%"';
        $this->assertSame($expected, Args::escape($arg, true, true));
    }

    /**
     * Test various arguments are received as expected
     *
     */
    public function testCommandLine()
    {
        if (!defined('PHP_BINARY')) {
            $this->markTestSkipped('PHP_BINARY not available');
        }

        $params = array(PHP_BINARY, __DIR__.DIRECTORY_SEPARATOR.'args.php');
        $expected = array();

        foreach ($this->dataArguments() as $test) {
            $expected[] = $test[0];
        }

        $params = array_merge($params, $expected);
        $command = Args::escapeCommand($params);

        exec($command, $lines);
        $this->assertEquals($expected, $lines);
    }

    /**
     * Test data provider.
     *
     * Each named test is an array of:
     *   argument, win-expected, unix-expected, meta
     */
    public function dataArguments()
    {
        return array(
            // empty argument - must be quoted
            'empty'         => array('', '""', "''", 0),

            // whitespace <space> must be quoted
            'ws space'      => array('a b c', '"a b c"', "'a b c'", 0),

            // whitespace <tab> must be quoted
            'ws tab'        => array("a\tb\tc", "\"a\tb\tc\"", "'a\tb\tc'", 0),

            // no whitespace must not be quoted
            'no-ws'         => array('abc', 'abc', "'abc'", 0),

            // double-quotes must be backslash-escaped
            'dq'            => array('a"bc', 'a\"bc', "'a\"bc'", 0),

            // double-quotes must be backslash-escaped with preceeding backslashes doubled
            'dq-bslash'     => array('a\\"bc', 'a\\\\\"bc', "'a\\\"bc'", 0),

            // backslashes not preceeding a double-quote are treated as literal
            'bslash'        => array('ab\\\\c\\', 'ab\\\\c\\', "'ab\\\\c\\'", 0),

            // trailing backslashes must be doubled up when the argument is quoted
            'bslash dq'     => array('a b c\\\\', '"a b c\\\\\\\\"', "'a b c\\\\'", 0),

            // meta: double-quotes must be caret-escaped
            'meta-dq'       => array('a"bc', 'a\^"bc', "'a\"bc'", 1),

            // meta: outer double-quotes must be caret-escaped as well
            'meta dq'       => array('a "b" c', '^"a \^"b\^" c^"', "'a \"b\" c'", 1),

            // meta: percent expansion must be caret-escaped
            'meta-pc'       => array('%path%', '^%path^%', "'%path%'", 1),

            // meta: expansion must have two percent characters
            'meta-ex1'      => array('%path', '%path', "'%path'", 1),

            // meta: expansion must have have two surrounding percent characters
            'meta-ex2'      => array('%%path', '%%path', "'%%path'", 1),

            // meta: expansion must ignore exclamation marks
            'meta-ex3'      => array('!path!', '!path!', "'!path!'", 1),

            // meta: caret-escaping must escape all other meta chars (triggered by double-quote)
            'meta-all-dq'   => array('<>"&|()^', '^<^>\^"^&^|^(^)^^', "'<>\"&|()^'", 1),

            // other meta: no caret-escaping when whitespace in argument
            'other meta'    => array('<> &| ()^', '"<> &| ()^"', "'<> &| ()^'", 1),

            // other meta: quote escape chars when no whitespace in argument
            'other-meta'    => array('<>&|()^', '"<>&|()^"', "'<>&|()^'", 1),
        );
    }
}

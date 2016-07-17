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

use Winbox\Args as Winbox;

class ArgsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataArguments
     */
    public function testEscapeArgument($arg, $win, $unix, $meta)
    {
        if (defined('PHP_BINARY')) {
            $this->assertSame($win, Winbox::escapeArgument($arg, $meta));
        } else {
            $this->assertSame($unix, Winbox::escapeArgument($arg, $meta));
        }
    }

    public function testCommandLine()
    {
        if (!defined('PHP_BINARY')) {
            $this->markTestSkipped('PHP_BINARY not available');
        }

        $params = array(PHP_BINARY, __DIR__.DIRECTORY_SEPARATOR.'args.php');

        foreach ($this->dataArguments() as $test) {
            $expected[] = $test[0];
        }

        $params = array_merge($params, $expected);
        $callback = array('\Winbox\Args', 'escapeArgument');

        $command = implode(' ', array_map($callback, $params));
        exec($command, $lines);
        $this->assertEquals($lines, $expected);
    }

    public function dataArguments()
    {
        // argument, win-expected, unix-expected, meta
        return array(
            'empty' => array('', '""', "''", 0),
            'nspace' => array('abc', 'abc', "'abc'", 0),
            'spaced' => array('a b c', '"a b c"', "'a b c'", 0),
            'nspace-dq' => array('a"bc', '"a\"bc"', "'a\"bc'", 0),
            'spaced dq' => array('a "b" c', '"a \"b\" c"', "'a \"b\" c'", 0),
            'bslash' => array('ab\c\\', 'ab\c\\', "'ab\\c\\'", 0),
            'bslash dq' => array('a\\\\"bc', '"a\\\\\\\\\"bc"', "'a\\\\\"bc'", 0),
            'bslash end' => array('a b c\\\\', '"a b c\\\\\\\\"', "'a b c\\\\", 0),
            'nspace-pc' => array('%path%', '^%path^%', "'%path%'", 1),
            'spaced pc' => array('ab %path%c', '^"ab ^%path^%c^"', "'ab %path%'c", 1),
            'nspace-meta' => array('<>&|()^', '^<^>^&^|^(^)^^', "'<>&|()^'", 1),
            'spaced meta' => array('<> &| ()^', '"<> &| ()^"', "'<> &| ()^'", 1),
            'meta dq' => array('<>"&|()^', '^"^<^>\^"^&^|^(^)^^^"', "'<>\"&|()^'", 1),
        );
    }
}


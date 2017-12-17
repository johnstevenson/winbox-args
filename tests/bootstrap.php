<?php
/*
 * This file is part of the Winbox packages.
 *
 * (c) John Stevenson <john-stevenson@blueyonder.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

error_reporting(E_ALL);
$autoloader = require dirname(__DIR__).'/vendor/autoload.php';
$autoloader->addPsr4('Winbox\\Tests\\', __DIR__);

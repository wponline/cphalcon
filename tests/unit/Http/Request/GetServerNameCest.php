<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Test\Unit\Http\Request;

use Phalcon\Http\Request;
use UnitTester;

class GetServerNameCest
{
    /**
     * Tests Phalcon\Http\Request :: getServerName()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function httpRequestGetServerName(UnitTester $I)
    {
        $I->wantToTest('Http\Request - getServerName()');

        unset($_SERVER['SERVER_NAME']);
        $request = new Request();
        $I->assertEquals('localhost', $request->getServerName());

        $_SERVER['SERVER_NAME'] = 'dev.phalcon.io';
        $request = new Request();

        $I->assertEquals('dev.phalcon.io', $request->getServerName());
    }
}

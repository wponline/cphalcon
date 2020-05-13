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

class GetHTTPRefererCest
{
    /**
     * Tests Phalcon\Http\Request :: getHTTPReferer()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function httpRequestGetHTTPReferer(UnitTester $I)
    {
        $I->wantToTest('Http\Request - getHTTPReferer()');

        // Empty
        unset($_SERVER['HTTP_REFERER']);
        $request = new Request();

        $I->assertEmpty($request->getHTTPReferer());

        // Valid
        $_SERVER['HTTP_REFERER'] = 'http://some.site';
        $request = new Request();

        $expected = 'http://some.site';
        $actual   = $request->getHTTPReferer();
        $I->assertEquals($expected, $actual);
    }
}

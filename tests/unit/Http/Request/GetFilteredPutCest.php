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

use function file_put_contents;
use Phalcon\Http\Request;
use Phalcon\Storage\Exception;
use Phalcon\Test\Fixtures\Http\PhpStream;
use Phalcon\Test\Fixtures\Traits\DiTrait;

use function stream_wrapper_register;
use function stream_wrapper_restore;
use function stream_wrapper_unregister;
use UnitTester;

class GetFilteredPutCest
{
    use DiTrait;

    /**
     * Tests Phalcon\Http\Request :: getFilteredPut()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function httpRequestGetFilteredPut(UnitTester $I)
    {
        $I->wantToTest('Http\Request - getFilteredPut()');

        stream_wrapper_unregister('php');
        stream_wrapper_register('php', PhpStream::class);

        file_put_contents('php://input', 'no-id=24');
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        try {
            $container = $this->newService('factoryDefault');

            /** @var Request $request */
            $request = $container->get('request');
            $request
                ->setParameterFilters('id', ['absint'], ['put']);

            $expected = 24;
            $actual   = $request->getFilteredPut('id', 24);
            $I->assertEquals($expected, $actual);

            stream_wrapper_restore('php');
        } catch (Exception $e) {
            $I->fail($e->getMessage());
        }
    }
}

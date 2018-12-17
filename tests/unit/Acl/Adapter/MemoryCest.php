<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalconphp.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Test\Unit\Acl\Adapter;

use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Subject;
use Phalcon\Acl\Operation;
use Phalcon\Test\Fixtures\Acl\TestSubjectAware;
use Phalcon\Test\Fixtures\Acl\TestOperationAware;
use Phalcon\Test\Fixtures\Acl\TestOperationSubjectAware;
use PHPUnit\Framework\Exception;
use UnitTester;

class MemoryCest
{

    /**
     * Tests the addOperation for the same role twice
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclAddOperationTwiceReturnsFalse(UnitTester $I)
    {
        $acl     = new Memory();
        $aclOperation = new Operation('Administrators', 'Super User access');

        $acl->addOperation($aclOperation);
        $actual = $acl->addOperation($aclOperation);
        $I->assertFalse($actual);
    }

    /**
     * Tests the addOperation for the same role twice by key
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclAddOperationTwiceByKeyReturnsFalse(UnitTester $I)
    {
        $acl     = new Memory();
        $aclOperation = new Operation('Administrators', 'Super User access');

        $acl->addOperation($aclOperation);
        $actual = $acl->addOperation('Administrators');
        $I->assertFalse($actual);
    }

    /**
     * Tests the wildcard allow/deny
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclWildcardAllowDeny(UnitTester $I)
    {
        $acl = new Memory();
        $acl->setDefaultAction(Acl::DENY);

        $aclOperations = [
            'Admin'  => new Operation('Admin'),
            'Users'  => new Operation('Users'),
            'Guests' => new Operation('Guests'),
        ];

        $aclSubjects = [
            'welcome' => ['index', 'about'],
            'account' => ['index'],
        ];

        foreach ($aclOperations as $role => $object) {
            $acl->addOperation($object);
        }

        foreach ($aclSubjects as $resource => $actions) {
            $acl->addSubject(new Subject($resource), $actions);
        }
        $acl->allow("*", "welcome", "index");

        foreach ($aclOperations as $role => $object) {
            $actual = $acl->isAllowed($role, 'welcome', 'index');
            $I->assertTrue($actual);
        }

        $acl->deny("*", "welcome", "index");
        foreach ($aclOperations as $role => $object) {
            $actual = $acl->isAllowed($role, 'welcome', 'index');
            $I->assertFalse($actual);
        }
    }

    /**
     * Tests the isOperation with wrong keyword
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclIsOperationWithWrongKeyReturnsFalse(UnitTester $I)
    {
        $acl    = new Memory();
        $actual = $acl->isOperation('Wrong');
        $I->assertFalse($actual);
    }

    /**
     * Tests the ACL objects default action
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclObjectsWithDefaultAction(UnitTester $I)
    {
        $acl         = new Memory();
        $aclOperation     = new Operation('Administrators', 'Super User access');
        $aclSubject = new Subject('Customers', 'Customer management');

        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation($aclOperation);
        $acl->addSubject($aclSubject, ['search', 'destroy']);

        $expected = Acl::DENY;
        $actual   = $acl->isAllowed('Administrators', 'Customers', 'search');
        $I->assertEquals($expected, $actual);

        $acl         = new Memory();
        $aclOperation     = new Operation('Administrators', 'Super User access');
        $aclSubject = new Subject('Customers', 'Customer management');

        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation($aclOperation);
        $acl->addSubject($aclSubject, ['search', 'destroy']);

        $expected = Acl::DENY;
        $actual   = $acl->isAllowed('Administrators', 'Customers', 'destroy');
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests the ACL objects
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclObjects(UnitTester $I)
    {
        $acl         = new Memory();
        $aclOperation     = new Operation('Administrators', 'Super User access');
        $aclSubject = new Subject('Customers', 'Customer management');

        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation($aclOperation);
        $acl->addSubject($aclSubject, ['search', 'destroy']);

        $acl->allow('Administrators', 'Customers', 'search');
        $acl->deny('Administrators', 'Customers', 'destroy');

        $expected = Acl::ALLOW;
        $actual   = $acl->isAllowed('Administrators', 'Customers', 'search');
        $I->assertEquals($expected, $actual);

        $acl         = new Memory();
        $aclOperation     = new Operation('Administrators', 'Super User access');
        $aclSubject = new Subject('Customers', 'Customer management');

        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation($aclOperation);
        $acl->addSubject($aclSubject, ['search', 'destroy']);

        $acl->allow('Administrators', 'Customers', 'search');
        $acl->deny('Administrators', 'Customers', 'destroy');

        $expected = Acl::DENY;
        $actual   = $acl->isAllowed('Administrators', 'Customers', 'destroy');
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests serializing the ACL
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2014-10-04
     */
    public function testAclSerialize(UnitTester $I)
    {
        $filename = $I->getNewFileName('acl', 'log');

        $acl         = new Memory();
        $aclOperation     = new Operation('Administrators', 'Super User access');
        $aclSubject = new Subject('Customers', 'Customer management');

        $acl->addOperation($aclOperation);
        $acl->addSubject($aclSubject, ['search', 'destroy']);

        $acl->allow('Administrators', 'Customers', 'search');
        $acl->deny('Administrators', 'Customers', 'destroy');

        $contents = serialize($acl);
        file_put_contents(cacheFolder($filename), $contents);

        $acl = null;

        $contents = file_get_contents(cacheFolder($filename));

        $I->safeDeleteFile(cacheFolder($filename));

        $acl    = unserialize($contents);
        $actual = ($acl instanceof Memory);
        $I->assertTrue($actual);

        $actual = $acl->isOperation('Administrators');
        $I->assertTrue($actual);

        $actual = $acl->isSubject('Customers');
        $I->assertTrue($actual);

        $expected = Acl::ALLOW;
        $actual   = $acl->isAllowed('Administrators', 'Customers', 'search');
        $I->assertEquals($expected, $actual);

        $expected = Acl::DENY;
        $actual   = $acl->isAllowed('Administrators', 'Customers', 'destroy');
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests negation of inherited roles
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/65
     *
     * @author  Phalcon Team <team@phalconphp.com>
     * @since   2014-10-04
     */
    public function testAclNegationOfInheritedOperations(UnitTester $I)
    {
        $acl = new Memory;
        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation('Guests');
        $acl->addOperation('Members', 'Guests');

        $acl->addSubject('Login', ['help', 'index']);

        $acl->allow('Guests', 'Login', '*');
        $acl->deny('Guests', 'Login', ['help']);
        $acl->deny('Members', 'Login', ['index']);

        $actual = (bool) $acl->isAllowed('Members', 'Login', 'index');
        $I->assertFalse($actual);

        $actual = (bool) $acl->isAllowed('Guests', 'Login', 'index');
        $I->assertTrue($actual);

        $actual = (bool) $acl->isAllowed('Guests', 'Login', 'help');
        $I->assertFalse($actual);
    }

    /**
     * Tests ACL Subjects with numeric values
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/1513
     *
     * @author  Phalcon Team <team@phalconphp.com>
     * @since   2014-10-04
     */
    public function testAclSubjectsWithNumericValues(UnitTester $I)
    {
        $acl = new Memory;
        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation(new Operation('11'));
        $acl->addSubject(new Subject('11'), ['index']);

        $actual = $acl->isSubject('11');
        $I->assertTrue($actual);
    }

    /**
     * Tests function in Acl Allow Method
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/11235
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2015-12-16
     */
    public function testAclAllowFunction(UnitTester $I)
    {
        $acl = new Memory;
        $acl->setDefaultAction(Acl::DENY);
        $acl->addOperation('Guests');
        $acl->addOperation('Members', 'Guests');
        $acl->addOperation('Admins', 'Members');
        $acl->addSubject('Post', ['update']);

        $guest         = new TestOperationAware(1, 'Guests');
        $member        = new TestOperationAware(2, 'Members');
        $anotherMember = new TestOperationAware(3, 'Members');
        $admin         = new TestOperationAware(4, 'Admins');
        $model         = new TestSubjectAware(2, 'Post');

        $acl->deny('Guests', 'Post', 'update');
        $acl->allow('Members', 'Post', 'update', function (TestOperationAware $user, TestSubjectAware $model) {
            return $user->getId() == $model->getUser();
        });
        $acl->allow('Admins', 'Post', 'update');

        $actual = $acl->isAllowed($guest, $model, 'update');
        $I->assertFalse($actual);

        $actual = $acl->isAllowed($member, $model, 'update');
        $I->assertTrue($actual);

        $actual = $acl->isAllowed($anotherMember, $model, 'update');
        $I->assertFalse($actual);

        $actual = $acl->isAllowed($admin, $model, 'update');
        $I->assertTrue($actual);
    }

    /**
     * Tests function in Acl Allow Method
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/12004
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2016-07-22
     */
    public function testIssue12004(UnitTester $I)
    {
        $acl = new Memory();

        $acl->setDefaultAction(Acl::DENY);

        $roleGuest      = new Operation("guest");
        $roleUser       = new Operation("user");
        $roleAdmin      = new Operation("admin");
        $roleSuperAdmin = new Operation("superadmin");

        $acl->addOperation($roleGuest);
        $acl->addOperation($roleUser, $roleGuest);
        $acl->addOperation($roleAdmin, $roleUser);
        $acl->addOperation($roleSuperAdmin, $roleAdmin);

        $acl->addSubject("payment", ["paypal", "facebook",]);

        $acl->allow($roleGuest->getName(), "payment", "paypal");
        $acl->allow($roleGuest->getName(), "payment", "facebook");
        $acl->allow($roleUser->getName(), "payment", "*");

        $actual = $acl->isAllowed($roleUser->getName(), "payment", "notSet");
        $I->assertTrue($actual);
        $actual = $acl->isAllowed($roleUser->getName(), "payment", "*");
        $I->assertTrue($actual);
        $actual = $acl->isAllowed($roleAdmin->getName(), "payment", "notSet");
        $I->assertTrue($actual);
        $actual = $acl->isAllowed($roleAdmin->getName(), "payment", "*");
        $I->assertTrue($actual);
    }

    /**
     * Tests function in Acl Allow Method without arguments
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/12094
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2016-06-05
     */
    public function testAclAllowFunctionNoArguments(UnitTester $I)
    {
        $acl = new Memory;
        $acl->setDefaultAction(Acl::ALLOW);
        $acl->setNoArgumentsDefaultAction(Acl::DENY);
        $acl->addOperation('Guests');
        $acl->addOperation('Members', 'Guests');
        $acl->addOperation('Admins', 'Members');
        $acl->addSubject('Post', ['update']);

        $guest         = new TestOperationAware(1, 'Guests');
        $member        = new TestOperationAware(2, 'Members');
        $anotherMember = new TestOperationAware(3, 'Members');
        $admin         = new TestOperationAware(4, 'Admins');
        $model         = new TestSubjectAware(2, 'Post');

        $acl->allow('Guests', 'Post', 'update', function ($parameter) {
            return $parameter % 2 == 0;
        });
        $acl->allow('Members', 'Post', 'update', function ($parameter) {
            return $parameter % 2 == 0;
        });
        $acl->allow('Admins', 'Post', 'update');

        $actual = @$acl->isAllowed($guest, $model, 'update');
        $I->assertFalse($actual);
        $actual = @$acl->isAllowed($member, $model, 'update');
        $I->assertFalse($actual);
        $actual = @$acl->isAllowed($anotherMember, $model, 'update');
        $I->assertFalse($actual);
        $actual = @$acl->isAllowed($admin, $model, 'update');
        $I->assertTrue($actual);
    }

    /**
     * Tests function in Acl Allow Method without arguments
     *
     * @issue  https://github.com/phalcon/cphalcon/issues/12094
     * @author                   Wojciech Slawski <jurigag@gmail.com>
     * @since                    2016-06-05
     */
    public function testAclAllowFunctionNoArgumentsWithWarning(UnitTester $I)
    {
        $I->expectThrowable(
            new Exception(
                "You didn't provide any parameters when check Guests can " .
                "update Post. We will use default action when no arguments.",
                1024
            ),
            function () {
                $acl = new Memory;
                $acl->setDefaultAction(Acl::ALLOW);
                $acl->setNoArgumentsDefaultAction(Acl::DENY);
                $acl->addOperation('Guests');
                $acl->addOperation('Members', 'Guests');
                $acl->addOperation('Admins', 'Members');
                $acl->addSubject('Post', ['update']);

                $guest         = new TestOperationAware(1, 'Guests');
                $member        = new TestOperationAware(2, 'Members');
                $anotherMember = new TestOperationAware(3, 'Members');
                $admin         = new TestOperationAware(4, 'Admins');
                $model         = new TestSubjectAware(2, 'Post');

                $acl->allow('Guests', 'Post', 'update', function ($parameter) {
                    return $parameter % 2 == 0;
                });
                $acl->allow('Members', 'Post', 'update', function ($parameter) {
                    return $parameter % 2 == 0;
                });
                $acl->allow('Admins', 'Post', 'update');

                $actual = $acl->isAllowed($guest, $model, 'update');
                $I->assertFalse($actual);
                $actual = $acl->isAllowed($member, $model, 'update');
                $I->assertFalse($actual);
                $actual = $acl->isAllowed($anotherMember, $model, 'update');
                $I->assertFalse($actual);
                $actual = $acl->isAllowed($admin, $model, 'update');
                $I->assertTrue($actual);
            }
        );
    }

    /**
     * Tests acl with adding new rule for role after adding wildcard rule
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/2648
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2016-10-01
     */
    public function testWildCardLastOperation(UnitTester $I)
    {
        $acl = new Memory();
        $acl->addOperation(new Operation("Guests"));
        $acl->addSubject(new Subject('Post'), ['index', 'update', 'create']);

        $acl->allow('Guests', 'Post', 'create');
        $acl->allow('*', 'Post', 'index');
        $acl->allow('Guests', 'Post', 'update');

        $actual = $acl->isAllowed('Guests', 'Post', 'create');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed('Guests', 'Post', 'index');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed('Guests', 'Post', 'update');
        $I->assertTrue($actual);
    }

    /**
     * Tests adding wildcard rule second time
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/2648
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2016-10-01
     */
    public function testWildCardSecondTime(UnitTester $I)
    {
        $acl = new Memory();
        $acl->addOperation(new Operation("Guests"));
        $acl->addSubject(new Subject('Post'), ['index', 'update', 'create']);

        $acl->allow('Guests', 'Post', 'create');
        $acl->allow('*', 'Post', 'index');
        $acl->allow('*', 'Post', 'update');

        $actual = $acl->isAllowed('Guests', 'Post', 'create');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed('Guests', 'Post', 'index');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed('Guests', 'Post', 'update');
        $I->assertTrue($actual);
    }

    /**
     * Tests adding wildcard rule second time
     *
     * @issue   https://github.com/phalcon/cphalcon/issues/12573
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2017-01-25
     */
    public function testDefaultAction(UnitTester $I)
    {
        $acl = new Memory();
        $acl->setDefaultAction(Acl::DENY);
        $acl->addSubject(new Acl\Subject('Post'), ['index', 'update', 'create']);
        $acl->addOperation(new Operation('Guests'));

        $acl->allow('Guests', 'Post', 'index');
        $actual = $acl->isAllowed('Guests', 'Post', 'index');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed('Guests', 'Post', 'update');
        $I->assertFalse($actual);
    }

    /**
     * Tests role and resource objects as isAllowed parameters
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2017-02-15
     */
    public function testOperationSubjectObjects(UnitTester $I)
    {
        $acl = new Memory();
        $acl->setDefaultAction(Acl::DENY);
        $role     = new Operation('Guests');
        $resource = new Subject('Post');
        $acl->addOperation($role);
        $acl->addSubject($resource, ['index', 'update', 'create']);

        $acl->allow('Guests', 'Post', 'index');

        $actual = $acl->isAllowed($role, $resource, 'index');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed($role, $resource, 'update');
        $I->assertFalse($actual);
    }

    /**
     * Tests role and resource objects as isAllowed parameters of the same class
     *
     * @author  Wojciech Slawski <jurigag@gmail.com>
     * @since   2017-02-15
     */
    public function testOperationSubjectSameClassObjects(UnitTester $I)
    {
        $acl = new Memory();
        $acl->setDefaultAction(Acl::DENY);
        $role     = new TestOperationSubjectAware(1, 'User', 'Admin');
        $resource = new TestOperationSubjectAware(2, 'User', 'Admin');
        $acl->addOperation('Admin');
        $acl->addSubject('User', ['update']);
        $acl->allow(
            'Admin',
            'User',
            ['update'],
            function (TestOperationSubjectAware $admin, TestOperationSubjectAware $user) {
                return $admin->getUser() == $user->getUser();
            }
        );

        $actual = $acl->isAllowed($role, $resource, 'update');
        $I->assertFalse($actual);
        $actual = $acl->isAllowed($role, $role, 'update');
        $I->assertTrue($actual);
        $actual = $acl->isAllowed($resource, $resource, 'update');
        $I->assertTrue($actual);
    }

    /**
     * Tests negation of multiple inherited roles
     *
     *
     * @author  cq-z <64899484@qq.com>
     * @since   2018-10-10
     */
    public function testAclNegationOfMultipleInheritedOperations(UnitTester $I)
    {
        $acl = new Memory;
        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation('Guests');
        $acl->addOperation('Guests2');
        $acl->addOperation('Members', ['Guests', 'Guests2']);

        $acl->addSubject('Login', ['help', 'index']);

        $acl->allow('Guests', 'Login', '*');
        $acl->deny('Guests2', 'Login', ['help']);
        $acl->deny('Members', 'Login', ['index']);

        $actual = (bool) $acl->isAllowed('Members', 'Login', 'index');
        $I->assertFalse($actual);

        $actual = (bool) $acl->isAllowed('Guests', 'Login', 'help');
        $I->assertTrue($actual);

        $actual = (bool) $acl->isAllowed('Members', 'Login', 'help');
        $I->assertTrue($actual);
    }

    /**
     * Tests negation of multilayer inherited roles
     *
     *
     * @author  cq-z <64899484@qq.com>
     * @since   2018-10-10
     */
    public function testAclNegationOfMultilayerInheritedOperations(UnitTester $I)
    {
        $acl = new Memory;
        $acl->setDefaultAction(Acl::DENY);

        $acl->addOperation('Guests1');
        $acl->addOperation('Guests12', 'Guests1');
        $acl->addOperation('Guests2');
        $acl->addOperation('Guests22', 'Guests2');
        $acl->addOperation('Members', ['Guests12', 'Guests22']);

        $acl->addSubject('Login', ['help', 'index']);
        $acl->addSubject('Logout', ['help', 'index']);

        $acl->allow('Guests1', 'Login', '*');
        $acl->deny('Guests12', 'Login', ['help']);

        $acl->deny('Guests2', 'Logout', '*');
        $acl->allow('Guests22', 'Logout', ['index']);

        $actual = (bool) $acl->isAllowed('Members', 'Login', 'index');
        $I->assertTrue($actual);

        $actual = (bool) $acl->isAllowed('Members', 'Login', 'help');
        $I->assertFalse($actual);

        $actual = (bool) $acl->isAllowed('Members', 'Logout', 'help');
        $I->assertFalse($actual);

        $actual = (bool) $acl->isAllowed('Members', 'Login', 'index');
        $I->assertTrue($actual);
    }
}

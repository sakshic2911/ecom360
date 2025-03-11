<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RolePermissionMenusTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RolePermissionMenusTable Test Case
 */
class RolePermissionMenusTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RolePermissionMenusTable
     */
    protected $RolePermissionMenus;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.RolePermissionMenus',
        'app.Menus',
        'app.Roles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('RolePermissionMenus') ? [] : ['className' => RolePermissionMenusTable::class];
        $this->RolePermissionMenus = $this->getTableLocator()->get('RolePermissionMenus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->RolePermissionMenus);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\RolePermissionMenusTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\RolePermissionMenusTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

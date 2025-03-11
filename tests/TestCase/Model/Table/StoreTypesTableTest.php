<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\StoreTypesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\StoreTypesTable Test Case
 */
class StoreTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\StoreTypesTable
     */
    protected $StoreTypes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.StoreTypes',
        'app.ResourcesAssignStoretype',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('StoreTypes') ? [] : ['className' => StoreTypesTable::class];
        $this->StoreTypes = $this->getTableLocator()->get('StoreTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->StoreTypes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\StoreTypesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

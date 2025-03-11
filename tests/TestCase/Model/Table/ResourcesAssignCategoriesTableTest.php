<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResourcesAssignCategoriesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResourcesAssignCategoriesTable Test Case
 */
class ResourcesAssignCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourcesAssignCategoriesTable
     */
    protected $ResourcesAssignCategories;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.ResourcesAssignCategories',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ResourcesAssignCategories') ? [] : ['className' => ResourcesAssignCategoriesTable::class];
        $this->ResourcesAssignCategories = $this->getTableLocator()->get('ResourcesAssignCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->ResourcesAssignCategories);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\ResourcesAssignCategoriesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

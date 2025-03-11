<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SupportResourcesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SupportResourcesTable Test Case
 */
class SupportResourcesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SupportResourcesTable
     */
    protected $SupportResources;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SupportResources',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SupportResources') ? [] : ['className' => SupportResourcesTable::class];
        $this->SupportResources = $this->getTableLocator()->get('SupportResources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SupportResources);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SupportResourcesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DeactivationTemplatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DeactivationTemplatesTable Test Case
 */
class DeactivationTemplatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DeactivationTemplatesTable
     */
    protected $DeactivationTemplates;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.DeactivationTemplates',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DeactivationTemplates') ? [] : ['className' => DeactivationTemplatesTable::class];
        $this->DeactivationTemplates = $this->getTableLocator()->get('DeactivationTemplates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->DeactivationTemplates);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\DeactivationTemplatesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TicketDocsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TicketDocsTable Test Case
 */
class TicketDocsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TicketDocsTable
     */
    protected $TicketDocs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.TicketDocs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('TicketDocs') ? [] : ['className' => TicketDocsTable::class];
        $this->TicketDocs = $this->getTableLocator()->get('TicketDocs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->TicketDocs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\TicketDocsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

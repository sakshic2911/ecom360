<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersIssuesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersIssuesTable Test Case
 */
class UsersIssuesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersIssuesTable
     */
    protected $UsersIssues;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.UsersIssues',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('UsersIssues') ? [] : ['className' => UsersIssuesTable::class];
        $this->UsersIssues = $this->getTableLocator()->get('UsersIssues', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->UsersIssues);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\UsersIssuesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

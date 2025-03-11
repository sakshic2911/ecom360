<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TicketDocsFixture
 */
class TicketDocsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'ticket_id' => 1,
                'doc_type' => 1,
                'document' => 'Lorem ipsum dolor sit amet',
                'category' => 1,
                'created_at' => 1741264264,
                'updated_at' => 1741264264,
            ],
        ];
        parent::init();
    }
}

<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TicketWatchersFixture
 */
class TicketWatchersFixture extends TestFixture
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
                'watcher_id' => 1,
                'created_at' => 1741266366,
                'updated_at' => 1741266366,
            ],
        ];
        parent::init();
    }
}

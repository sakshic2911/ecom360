<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TicketRatingsFixture
 */
class TicketRatingsFixture extends TestFixture
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
                'user_id' => 1,
                'user_type' => 1,
                'rating' => 'Lorem ipsum dolor sit amet',
                'created_at' => 1741692441,
                'updated_at' => 1741692441,
            ],
        ];
        parent::init();
    }
}

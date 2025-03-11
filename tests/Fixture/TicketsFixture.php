<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TicketsFixture
 */
class TicketsFixture extends TestFixture
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
                'ticket_identity' => 'Lorem ipsum dolor sit amet',
                'client_id' => 1,
                'store_id' => 1,
                'title' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'issue_type' => 1,
                'support_staff' => 1,
                'store_specific' => 'Lorem ipsum dolor sit a',
                'status' => 1,
                'delete_tickets' => 1,
                'mark_important' => 1,
                'mark_flag' => 1,
                'created_at' => 1741264292,
                'closed_at' => '2025-03-06 12:31:32',
                'closed_by' => 1,
                'assigned_at' => '2025-03-06 12:31:32',
                'updated_at' => 1741264292,
            ],
        ];
        parent::init();
    }
}

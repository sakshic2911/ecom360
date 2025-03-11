<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * LabelsFixture
 */
class LabelsFixture extends TestFixture
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
                'label_name' => 'Lorem ipsum dolor sit amet',
                'ticket_id' => 1,
                'created_at' => 1741266591,
                'updated_at' => 1741266591,
            ],
        ];
        parent::init();
    }
}

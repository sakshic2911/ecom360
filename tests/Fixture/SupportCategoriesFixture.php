<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SupportCategoriesFixture
 */
class SupportCategoriesFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'ranking' => 1,
                'is_deleted' => 1,
                'created_at' => '2025-03-06 09:28:22',
                'updated_at' => 1741253302,
            ],
        ];
        parent::init();
    }
}

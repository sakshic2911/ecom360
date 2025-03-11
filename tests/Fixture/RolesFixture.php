<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RolesFixture
 */
class RolesFixture extends TestFixture
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
                'role_name' => 'Lorem ipsum dolor sit amet',
                'status' => 1,
                'delete_role' => 1,
                'created_at' => 1741330340,
                'updated_at' => 1741330340,
            ],
        ];
        parent::init();
    }
}

<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserPermissionsFixture
 */
class UserPermissionsFixture extends TestFixture
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
                'user_id' => 1,
                'menu_id' => 1,
                'permission' => 1,
                'type' => 'Lorem ipsum dolor sit amet',
                'created_at' => 1741331339,
                'updated_at' => 1741331339,
            ],
        ];
        parent::init();
    }
}

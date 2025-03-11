<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RolePermissionMenusFixture
 */
class RolePermissionMenusFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'role_permission_menus';
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
                'menu_id' => 1,
                'role_id' => 1,
                'permission' => 1,
                'created_at' => 1741330463,
                'updated_at' => 1741330463,
            ],
        ];
        parent::init();
    }
}

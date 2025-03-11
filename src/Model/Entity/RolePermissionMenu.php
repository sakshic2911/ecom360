<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * RolePermissionMenu Entity
 *
 * @property int $id
 * @property int $menu_id
 * @property int $role_id
 * @property int $permission
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\Menu $menu
 * @property \App\Model\Entity\Role $role
 */
class RolePermissionMenu extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'menu_id' => true,
        'role_id' => true,
        'permission' => true,
        'created_at' => true,
        'updated_at' => true,
        'menu' => true,
        'role' => true,
    ];
}

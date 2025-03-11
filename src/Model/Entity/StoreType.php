<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StoreType Entity
 *
 * @property int $id
 * @property string $store_name
 * @property int $view
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\ResourcesAssignStoretype[] $resources_assign_storetype
 */
class StoreType extends Entity
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
        'store_name' => true,
        'view' => true,
        'created_at' => true,
        'updated_at' => true,
        'resources_assign_storetype' => true,
    ];
}

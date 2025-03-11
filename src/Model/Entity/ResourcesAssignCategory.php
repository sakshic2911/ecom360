<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ResourcesAssignCategory Entity
 *
 * @property int $id
 * @property int $resources_id
 * @property int $category_id
 * @property int $ranking
 * @property int $is_deleted
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 */
class ResourcesAssignCategory extends Entity
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
        'resources_id' => true,
        'category_id' => true,
        'ranking' => true,
        'is_deleted' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}

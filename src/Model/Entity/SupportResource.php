<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SupportResource Entity
 *
 * @property int $id
 * @property string $question
 * @property string|null $description
 * @property string $for_type
 * @property string|null $url
 * @property string|null $embed_code
 * @property string|null $tags
 * @property int $is_deleted
 * @property int $status
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime $updated_at
 */
class SupportResource extends Entity
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
        'question' => true,
        'description' => true,
        'for_type' => true,
        'url' => true,
        'embed_code' => true,
        'tags' => true,
        'is_deleted' => true,
        'status' => true,
        'created_at' => true,
        'updated_at' => true,
    ];
}

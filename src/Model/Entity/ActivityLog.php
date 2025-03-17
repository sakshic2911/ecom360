<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ActivityLog Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $page
 * @property int $store_id
 * @property int|null $page_related_id
 * @property string $task
 * @property string|null $old_value
 * @property string|null $management_fee
 * @property string|null $previous_amount
 * @property \Cake\I18n\FrozenDate|null $previous_date
 * @property int|null $issue_type
 * @property \Cake\I18n\FrozenTime $created_at
 *
 * @property \App\Model\Entity\User $user
 */
class ActivityLog extends Entity
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
        'user_id' => true,
        'page' => true,
        'store_id' => true,
        'page_related_id' => true,
        'task' => true,
        'old_value' => true,
        'management_fee' => true,
        'previous_amount' => true,
        'previous_date' => true,
        'issue_type' => true,
        'created_at' => true,
        'user' => true,
    ];
}

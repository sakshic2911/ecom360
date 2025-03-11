<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ticket Entity
 *
 * @property int $id
 * @property string|null $ticket_identity
 * @property int $client_id
 * @property int $store_id
 * @property string|null $title
 * @property string|null $description
 * @property int $issue_type
 * @property int|null $support_staff
 * @property string|null $store_specific
 * @property int $status
 * @property int $delete_tickets
 * @property int $mark_important
 * @property int $mark_flag
 * @property \Cake\I18n\FrozenTime $created_at
 * @property \Cake\I18n\FrozenTime|null $closed_at
 * @property int|null $closed_by
 * @property \Cake\I18n\FrozenTime|null $assigned_at
 * @property \Cake\I18n\FrozenTime $updated_at
 *
 * @property \App\Model\Entity\TicketDoc[] $ticket_docs
 */
class Ticket extends Entity
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
        'ticket_identity' => true,
        'client_id' => true,
        'store_id' => true,
        'title' => true,
        'description' => true,
        'issue_type' => true,
        'support_staff' => true,
        'store_specific' => true,
        'status' => true,
        'delete_tickets' => true,
        'mark_important' => true,
        'mark_flag' => true,
        'created_at' => true,
        'closed_at' => true,
        'closed_by' => true,
        'assigned_at' => true,
        'updated_at' => true,
        'ticket_docs' => true,
    ];
}

<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tickets Model
 *
 * @property \App\Model\Table\TicketDocsTable&\Cake\ORM\Association\HasMany $TicketDocs
 *
 * @method \App\Model\Entity\Ticket newEmptyEntity()
 * @method \App\Model\Entity\Ticket newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ticket get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ticket findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Ticket patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ticket|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ticket[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TicketsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('tickets');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->hasMany('TicketDocs', [
            'foreignKey' => 'ticket_id',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('ticket_identity')
            ->maxLength('ticket_identity', 255)
            ->allowEmptyString('ticket_identity');

        $validator
            ->integer('client_id')
            ->requirePresence('client_id', 'create')
            ->notEmptyString('client_id');

        $validator
            ->integer('store_id')
            ->requirePresence('store_id', 'create')
            ->notEmptyString('store_id');

        $validator
            ->scalar('title')
            ->allowEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->integer('issue_type')
            ->requirePresence('issue_type', 'create')
            ->notEmptyString('issue_type');

        $validator
            ->integer('support_staff')
            ->allowEmptyString('support_staff');

        $validator
            ->scalar('store_specific')
            ->maxLength('store_specific', 25)
            ->allowEmptyString('store_specific');

        $validator
            ->integer('status')
            ->notEmptyString('status');

        $validator
            ->integer('delete_tickets')
            ->notEmptyString('delete_tickets');

        $validator
            ->integer('mark_important')
            ->notEmptyString('mark_important');

        $validator
            ->integer('mark_flag')
            ->notEmptyString('mark_flag');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('closed_at')
            ->allowEmptyDateTime('closed_at');

        $validator
            ->integer('closed_by')
            ->allowEmptyString('closed_by');

        $validator
            ->dateTime('assigned_at')
            ->allowEmptyDateTime('assigned_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }
}

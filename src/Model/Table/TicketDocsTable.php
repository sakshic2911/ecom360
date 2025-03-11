<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TicketDocs Model
 *
 * @method \App\Model\Entity\TicketDoc newEmptyEntity()
 * @method \App\Model\Entity\TicketDoc newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\TicketDoc[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TicketDoc get($primaryKey, $options = [])
 * @method \App\Model\Entity\TicketDoc findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\TicketDoc patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TicketDoc[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\TicketDoc|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TicketDoc saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TicketDoc[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TicketDoc[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\TicketDoc[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\TicketDoc[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class TicketDocsTable extends Table
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

        $this->setTable('ticket_docs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->integer('ticket_id')
            ->requirePresence('ticket_id', 'create')
            ->notEmptyString('ticket_id');

        $validator
            ->integer('doc_type')
            ->allowEmptyString('doc_type');

        $validator
            ->scalar('document')
            ->maxLength('document', 150)
            ->allowEmptyString('document');

        $validator
            ->integer('category')
            ->notEmptyString('category');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }
}

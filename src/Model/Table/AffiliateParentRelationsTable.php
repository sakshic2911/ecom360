<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AffiliateParentRelations Model
 *
 * @method \App\Model\Entity\AffiliateParentRelation newEmptyEntity()
 * @method \App\Model\Entity\AffiliateParentRelation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation get($primaryKey, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\AffiliateParentRelation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AffiliateParentRelationsTable extends Table
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

        $this->setTable('affiliate_parent_relations');
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
            ->integer('client_id')
            ->requirePresence('client_id', 'create')
            ->notEmptyString('client_id');

        $validator
            ->integer('affiliate_id')
            ->notEmptyString('affiliate_id');

        $validator
            ->integer('parent_affiliate_id')
            ->notEmptyString('parent_affiliate_id');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }
}

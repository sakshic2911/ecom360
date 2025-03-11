<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StoreTypes Model
 *
 * @property \App\Model\Table\ResourcesAssignStoretypeTable&\Cake\ORM\Association\HasMany $ResourcesAssignStoretype
 *
 * @method \App\Model\Entity\StoreType newEmptyEntity()
 * @method \App\Model\Entity\StoreType newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\StoreType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StoreType get($primaryKey, $options = [])
 * @method \App\Model\Entity\StoreType findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\StoreType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StoreType[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\StoreType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StoreType[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StoreType[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\StoreType[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StoreType[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StoreTypesTable extends Table
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

        $this->setTable('store_types');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('ResourcesAssignStoretype', [
            'foreignKey' => 'store_type_id',
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
            ->scalar('store_name')
            ->maxLength('store_name', 100)
            ->requirePresence('store_name', 'create')
            ->notEmptyString('store_name');

        $validator
            ->integer('view')
            ->notEmptyString('view');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }
}

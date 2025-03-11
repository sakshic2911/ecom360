<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ResourcesAssignCategories Model
 *
 * @method \App\Model\Entity\ResourcesAssignCategory newEmptyEntity()
 * @method \App\Model\Entity\ResourcesAssignCategory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ResourcesAssignCategory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ResourcesAssignCategoriesTable extends Table
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

        $this->setTable('resources_assign_categories');
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
            ->integer('resources_id')
            ->requirePresence('resources_id', 'create')
            ->notEmptyString('resources_id');

        $validator
            ->integer('category_id')
            ->requirePresence('category_id', 'create')
            ->notEmptyString('category_id');

        $validator
            ->integer('ranking')
            ->requirePresence('ranking', 'create')
            ->notEmptyString('ranking');

        $validator
            ->integer('is_deleted')
            ->notEmptyString('is_deleted');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }
}

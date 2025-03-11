<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UsersIssues Model
 *
 * @method \App\Model\Entity\UsersIssue newEmptyEntity()
 * @method \App\Model\Entity\UsersIssue newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UsersIssue[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersIssue get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersIssue findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UsersIssue patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersIssue[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersIssue|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersIssue saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersIssue[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersIssue[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersIssue[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersIssue[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class UsersIssuesTable extends Table
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

        $this->setTable('users_issues');
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
            ->integer('staff_id')
            ->requirePresence('staff_id', 'create')
            ->notEmptyString('staff_id');

        $validator
            ->integer('issue_type')
            ->requirePresence('issue_type', 'create')
            ->notEmptyString('issue_type');

        $validator
            ->dateTime('created_at')
            ->notEmptyDateTime('created_at');

        $validator
            ->dateTime('updated_at')
            ->notEmptyDateTime('updated_at');

        return $validator;
    }
}

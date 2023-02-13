<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Bugs Model
 *
 * @property &\Cake\ORM\Association\BelongsTo $Users
 * @property &\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Bug get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bug newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bug[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bug|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bug saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bug patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bug[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bug findOrCreate($search, callable $callback = null, $options = [])
 */
class BugsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     *
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp');

        $this->setTable('bugs');
        $this->setDisplayField('title');

        $this->belongsTo('AuthorUser', [
            'className'    => 'Users',
            'propertyName' => 'AuthorUser',
            'foreignKey'   => 'author_id',
            //            'joinType' => 'INNER',
        ]);
        $this->belongsTo('AssignedUser', [
            'className'    => 'Users',
            'propertyName' => 'AssignedUser',
            'foreignKey'   => 'assigned_id',
            //            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     *
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create')
            ->add('id', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('title')
            ->maxLength('title', 256)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 1000)
            ->allowEmptyString('description');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

        $validator
            ->integer('type')
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('author')
            ->maxLength('author', 256)
            ->allowEmptyString('author');

        $validator
            ->scalar('assigned')
            ->maxLength('assigned', 256)
            ->allowEmptyString('assigned');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     *
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['id']));
        $rules->add($rules->existsIn(['author_id'], 'AuthorUser'));
        $rules->add($rules->existsIn(['assigned_id'], 'AssignedUser'));

        return $rules;
    }
}

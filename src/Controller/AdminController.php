<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\MonthlyStatement;
// use Cake\Routing\Router;

class AdminController extends AppController
{
    private $session;
    public function initialize(): void
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('admin_dashboard');
        $this->stmtTbl = $this->getTableLocator()->get('MonthlyStatement');
        $this->userTbl = $this->getTableLocator()->get('Users');
        $this->storeTbl = $this->getTableLocator()->get('Store');
        $this->staffTbl = $this->getTableLocator()->get('StaffStore');
        $this->session = $this->request->getSession();
        $this->staffManagerTbl = $this->getTableLocator()->get('StaffAccountManagers');
    }
    public function index()
    {
        $this->session->write("activeUrl", "admin");

        $user = $this->session->read('user');
        if(!$user)
        {
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        if($user->user_type==2)
        {
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        $condition = [1];
        $storeCondition = ['status'=>1];
        $clientCondition = ['user_type'=>2];
        $staffStoreArray = [];
        if($user->user_type == 1 && $user->store_permission == 2 && $user->role_id != 16)
        {
            $staffStore = $this->staffTbl->find()->join([
                'groupList' => [
                    'table' =>'group_list',
                    'join' => 'inner',
                    'conditions' => '(StaffStore.group_id = groupList.id and groupList.delete_group=0)'
                ],
                'StoreGroup' => [
                    'table' =>'store_group',
                    'join' => 'inner',
                    'conditions' => '(groupList.id = StoreGroup.group_id)'
                ] 
            ])->select(['store_id'=>'StoreGroup.store_id'])->where(['staff_id'=>$user->id])->toArray();
           foreach($staffStore as $val)
           {
               $staffStoreArray[] = $val->store_id;
           }
           if($user->role_id == 15)
            {
                $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['assign_manager' => $user->id])->toArray(),'id');
                $staffStoreArray = array_merge($staffStoreArray,$storeIds);
            }
            if($user->role_id == 20)
            {
                // $userIds[] = 0;
                $userIds = array_column($this->userTbl->find()->select(['id'])->where(['parent_role' => $user->id])->toArray(),'id');
                if(count($userIds) == 0)
                $userIds[0] = 0;
                $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['OR' => [
                    'assign_manager' => $user->id,
                    'assign_manager IN' => $userIds
                    ]])->toArray(),'id');
                $staffStoreArray = array_merge($staffStoreArray,$storeIds);
            }
           $condition = ['Stores.id IN' => $staffStoreArray];
           $storeCondition['id IN'] = $staffStoreArray;
           $clientCondition['Stores.id IN'] = $staffStoreArray;
        }
        if($user->user_type == 1 && $user->role_id == 16)
        {
            $parent = $this->userTbl->find()->select(['parent_role'])->where(['id' => $user->id])->first();
            $temporary = array_column($this->staffManagerTbl->find()->select(['parent_role'])->where(['staff_id' => $user->id])->toArray(),'parent_role');
            if(count($temporary) == 0)
            $temporary[0] = 0;
            $staffStore = $this->staffTbl->find()->join([
                'groupList' => [
                    'table' =>'group_list',
                    'join' => 'inner',
                    'conditions' => '(StaffStore.group_id = groupList.id and groupList.delete_group=0)'
                ],
                'StoreGroup' => [
                    'table' =>'store_group',
                    'join' => 'inner',
                    'conditions' => '(groupList.id = StoreGroup.group_id)'
                ] 
            ])->select(['store_id'=>'StoreGroup.store_id'])->where(['OR' => [
                'staff_id'=>$parent->parent_role,
                'staff_id IN' => $temporary
                ]
                ])->toArray();
                $staffStoreArray = [];
            foreach($staffStore as $val)
            {
                $staffStoreArray[] = $val->store_id;
            }
            
            $storeIds = array_column($this->storeTbl->find()->select(['id'])->where(['OR' => [
                'assign_manager' => $parent->parent_role,
                'assign_manager IN' => $temporary,
            ]])->toArray(),'id');
            $staffStoreArray = array_merge($staffStoreArray,$storeIds);
            $condition = ['Stores.id IN' => $staffStoreArray];
           $storeCondition['id IN'] = $staffStoreArray;
           $clientCondition['Stores.id IN'] = $staffStoreArray;
        }
        
        $sales = $this->stmtTbl->find()->select(['sum' => $this->stmtTbl->find()->func()->sum('net_amazon_sale')])->toArray();
        $total_sales = $sales[0]->sum;
        //->join([
        //     'Stores' => [
        //         'table' => 'store',
        //         'type' => 'LEFT',
        //         'conditions' => 'MonthlyStatement.store_id = Stores.id'
        //     ]
        // ])->where($condition)
        $profit = $this->stmtTbl->find()->select(['sum' => $this->stmtTbl->find()->func()->sum('cash_profit')])->toArray();
        $total_profit = $profit[0]->sum;

        $total_clients = $this->userTbl->find()->where(['user_type'=>2])->count();

        $total_stores = $this->storeTbl->find()->where(['status'=>1])->count();

        $deactive_stores = $this->storeTbl->find()->where(['status'=>0])->count();

        $clients = $this->userTbl->find()->select([
            'id' => 'Users.id',
            'first_name' => 'Users.first_name',
            'last_name' => 'Users.last_name'
        ])->join([
            'Stores' => [
                'table' => 'store',
                'type' => 'LEFT',
                'conditions' => 'Users.id = Stores.clients'
            ]
        ])->where($clientCondition)->group('Users.id')->toArray();

        // $condition = [];
        $stores = [];
        if($user->user_type==2)
        {
            $stores = $this->storeTbl->find()->where(['status'=>1,'clients'=>$user->id,'onboarding_status !='=> 'Terminated'])->toArray();

            $condition = ['MonthlyStatement.client_id'=>$user->id];
        }

        $client_id  = 0;
        $store_id  = 0;
        $order_from  = date('Y-m-d',strtotime('-1 year'));
        $order_to = date('Y-m-d');
        if($this->request->is('post')) {

            $client_id  = $this->request->getData('client_id');
            if($user->user_type==2)
            {
                $client_id  = $user->id;
            }
            $store_id  = $this->request->getData('store_id');
            $order_from  = date('Y-m-d',strtotime($this->request->getData('order_from')));
            $order_to  =  date('Y-m-d',strtotime($this->request->getData('order_to')));

            $stores = $this->storeTbl->find()->where(['status'=>1,'clients'=>$client_id,'onboarding_status !='=> 'Terminated'])->toArray();

            $condition = [
                'MonthlyStatement.client_id'=>$client_id,
                'MonthlyStatement.store_id' => $store_id,
                'MonthlyStatement.order_date >=' => $order_from,
                'MonthlyStatement.order_date <=' => $order_to
            ];
        }

        $frontData = $this->stmtTbl->find()
                ->select([
                    'client_name' => 'CONCAT(Clients.first_name," ",Clients.last_name)',
                    'profile' => 'Clients.image',
                    'store_name' => 'Stores.store_name',
                    'sale' => $this->stmtTbl->find()->func()->sum('net_amazon_sale'),
                    'expense' => $this->stmtTbl->find()->func()->sum('total_cost'),
                    'profit' => $this->stmtTbl->find()->func()->sum('cash_profit')
                ])
                ->join([
                    'Clients' => [
                        'table' => 'users',
                        'type' => 'INNER',
                        'conditions' => 'Clients.id = MonthlyStatement.client_id',
                    ],
                    'Stores' => [
                        'table' => 'store',
                        'type' => 'INNER',
                        'conditions' => 'Stores.id = MonthlyStatement.store_id',
                    ]
                ])
                ->where($condition)
                ->group('MonthlyStatement.client_id,MonthlyStatement.store_id')
                ->limit(50)
                ->toArray();
        
        $this->set(compact('user','total_sales','total_profit','total_clients','total_stores','deactive_stores','frontData','clients','client_id','store_id','order_from','order_to','stores'));
    }
}
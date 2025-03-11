<?php

namespace App\View\Helper;

use Cake\ORM\Table;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class StoreTypeNameHelper extends Helper
{
    public function storeType($id = null)
    {
        // StoreType
        $storeNameTbl = TableRegistry::getTableLocator()->get('StoreTypeAssign');
        if ($id == null || $id == 0) {
            return $storeType = '--';
        } else {
            $storeNameData = $storeNameTbl->find()
                ->select(['store_name' => 'StoreType.store_name'])
                ->join([
                    'StoreType' => [
                        'table' => 'store_type',
                        'type' => 'LEFT',
                        'conditions' => 'StoreType.id = StoreTypeAssign.store_name_id'
                    ]
                ])
                ->where(['StoreTypeAssign.store_id' => $id])->toArray();
            $storeType = '';
            foreach ($storeNameData as $val) {
                $storeType .= "$val->store_name, ";
            }
            return trim($storeType,', ');
        }
    }
}

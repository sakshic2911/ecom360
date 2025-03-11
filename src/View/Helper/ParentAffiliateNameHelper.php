<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class ParentAffiliateNameHelper extends Helper
{
    public function affiliateName($aId)
    {

        $users_table =  TableRegistry::getTableLocator()->get('Users');
        if ($aId == null || $aId == 0) {
            $name = "--";
        } else {
            $userName = $users_table->find()->select(['first_name', 'last_name'])->where(['id' => $aId])->toArray();
            $name = ucfirst($userName[0]->first_name) . ' ' . ucfirst($userName[0]->last_name);
        }
        return $name;
    }
}
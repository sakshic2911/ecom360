<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class PlanNameHelper extends Helper
{
    public function planName($pId)
    {

        $planTbl =  TableRegistry::getTableLocator()->get('Plan');
        if ($pId == null || $pId == 0) {
            $name = "--";
        } else {
            $planName = $planTbl->find()->select(['plan_name'])->where(['id' => $pId])->toArray();
            $name = $planName[0]->plan_name;
        }
        return $name;
    }
}
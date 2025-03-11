<?php

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class AffiliateCommissionHelper extends Helper
{
    public function affiliateCommissionData($id, $store_type_id, $noOfSales)
    {
        $planAffiliateTbl =  TableRegistry::getTableLocator()->get('PlanAffiliateCommission');

        $affiliateCommissionData = $planAffiliateTbl->find()->where(['plan_id' => $id, 'store_type_id' => $store_type_id, 'no_of_sales' => $noOfSales])->toArray();

        if ($affiliateCommissionData[0]->affiliate_amount > 0) {

            $amount = ($affiliateCommissionData[0]->is_affiliate_numeric==0)?'$'.$affiliateCommissionData[0]->affiliate_amount:$affiliateCommissionData[0]->affiliate_amount.'%';
            return $amount;
        } else {
            return '--';
        }
    }
}
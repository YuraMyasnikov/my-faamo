<?php 

namespace frontend\controllers\actions\account;

use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrdersStatus;
use CmsModule\Shop\common\models\OrderStatusGroups;
use Yii;
use yii\base\Action;
use yii\data\ActiveDataProvider;


class OrdersAction extends Action 
{
    public function run($status_group = null)
    {
        $statusGroups = OrderStatusGroups::find()->all();
        $statusGroup = $status_group;
        $dataProvider = $this->activeDataProvider($statusGroups, $statusGroup);

        return $this->controller->render('orders', ['dataProvider' => $dataProvider, 'status_groups' => $statusGroups, 'status_group' => $statusGroup]);
    }  

    protected function activeDataProvider($statusGroups, $statusGroup): ActiveDataProvider 
    {
        $query = Orders::find()->alias('order')->where(['order.user_id' => Yii::$app->user->id])->orderBy(['order.created_at' => SORT_DESC]);

        if ($statusGroup !== null) {
            $query->innerJoin(OrdersStatus::tableName() . ' status', 'status.id = order.status_id');
            $query->andWhere(['status.group_id' => $statusGroup]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);   
    }
}
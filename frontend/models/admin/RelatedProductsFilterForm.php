<?php

namespace frontend\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class RelatedProductsFilterForm extends Model
{
    
    public $id;
    public $articul;
    
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'articul'], 'safe'],
        ];
    }

    public function load($data, $formName = null)
    {
        return parent::load($data, $formName);
    }

    public function search(array $params = [])
    {
        $this->load($params, 'RelatedProductsFilterForm');
        $this->id = Yii::$app->request->get('id');

        $query = \frontend\models\shop\Products::find();
        if(!empty($this->articul)) {
            $query->andWhere(['like', 'articul', $this->articul]);
        }
        $query->andWhere(['<>', 'id', $this->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort' => [
            //     'defaultOrder' => ['created_at' => SORT_DESC]
            // ],
            // 'pagination' => [
            //     'pageSizeLimit' => [20, 50, 100]
            // ],
        ]);
        return $dataProvider;
    }

    
}

<?php

namespace console\controllers;

use CmsModule\Shop\common\models\Categories;
use console\services\FeedInterface;
use console\services\YandexFeed;
use frontend\models\shop\Products;
use frontend\models\shop\Sku;
use Yii;
use yii\console\Controller;
use yii\di\Container;

class FeedController extends Controller
{
    /**
     * @var Container
     */
    private $container;

    public function __construct($id, $module, $config = [])
    {
        $this->container = Yii::$container;
        parent::__construct($id, $module, $config);
    }

    public function actionWebmaster()
    {
        $feed = $this->container->get(YandexFeed::class);
        $this->export($feed, 'webmaster');
    }

    private function export(FeedInterface $feed, $source)
    {
        $feedDirectory = Yii::getAlias(sprintf('@frontend/web/%s/feed/', $source));
        
        if (!file_exists($feedDirectory)) {
            mkdir($feedDirectory, 0777, true);
        }

        switch($source) {
            case 'webmaster':
                $file = $feedDirectory . 'webmaster.xml';
                 break;
        }

        $feed->withProducts($this->getProducts());
        $feed->export($file, $source);
    }

    private function getProducts()
    {
        return Products::find()
                        ->alias('product')
                        ->innerJoin(['category' => Categories::tableName()], 'category.id = product.category_id')
                        ->innerJoin(['sku' => Sku::tableName()], 'sku.product_id = product.id')
                        ->where(['sku.active' => true])
                        ->andWhere(['product.active' => true])
                        ->all();
    }

}

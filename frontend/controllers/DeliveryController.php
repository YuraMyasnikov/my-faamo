<?php
/**
 * @author Eduard Hachaturov <AHAIIOJIOH@gmail.com>
 * @link http://www.yiisoft.ru/
 * @copyright Copyright (c) 2017 YiiSoft.ru
 * @license http://www.yiisoft.ru/licenses/commercial
 */

namespace frontend\controllers;

use modules\delivery\DeliveryInterface;
use modules\delivery\Options;
use CmsModule\Shop\common\models\Sku;
use Yii;
use yii\web\Controller;
use yii\web\Request;
use CmsModule\Shop\common\models\Basket;
use CmsModule\Shop\common\models\DeliveryCompanies;

class DeliveryController extends Controller
{

    /** @var Request $request */
    public $request;

    /** @var DeliveryInterface $delivery */
    private $delivery;

    public function init()
    {
        parent::init();

        \Yii::$container->set(DeliveryInterface::class, $this);
    }

    public function __construct($id, $module, Request $request, DeliveryInterface $delivery, $config = [])
    {
        $this->request = $request;
        $this->delivery = $delivery;

        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        Yii::$app->response->format = 'json';

        return parent::behaviors();
    }

    public function actionCalculate()
    {
        $action = $this->request->isPost ? 'post' : 'get';
        $id = DeliveryCompanies::find()->where(['=', 'id', $this->request->get('id')])->one();
        $code = $id['code'];
        $address = [
            'zip' => $this->request->get('zip'),
            'fiasId' => $this->request->get('fiasId'),
            'kladrId' => $this->request->get('address')
        ];

        $productWeight = 0;
        $productHeight = 0;
        $productWidth = 0;
        $productLength = 0;
        $count = 1;
        
        $baskets = Basket::find()->where(['=', 'user_id', \Yii::$app->user->id])->all();
        foreach ($baskets as $basket) {
            $sku = Sku::findOne(['=', 'id', $basket->sku_id]);

            $productWeight += $sku->weight * $basket->count;
            $productHeight += $sku->height * $basket->count;
            $productWidth += $sku->width * $basket->count;
            $productLength += $sku->length * $basket->count;
            $count = $basket->count;
        }

        $products = [
            'width' => $productWidth,
            'height' => $productHeight,
            'length' => $productLength,
            'weight' => $productWeight,
            'count' => $count,
        ];


        $dispatchCode = $this->request->$action('dispatchCode', 'default');
        $receipt = $this->request->$action('receipt');

        if (null === $receipt) {
            $receipt = true;
        } elseif (is_string($receipt) && $receipt === 'false') {
            $receipt = $receipt !== 'false';
        }

        $_products = $this->delivery->createProduct($products);

        $options = new Options(
            [
                'sourceAddress' => $this->delivery->getAddress($dispatchCode),
                'targetAddress' => $this->delivery->createAddress($address),
                'products' => $_products,
                'receiptPoint' => $receipt,
                'declaredValue' => 550,
            ]
        );

        return $this->delivery->calculate($code, $options);
    }

    public function actionPoints()
    {
        $id = DeliveryCompanies::find()->where(['=', 'id', $this->request->get('id')])->one();
        $code = $id['code'];
        $address = [
            'zip' => $this->request->get('zip'),
            'fiasId' => $this->request->get('fiasId'),
            'kladrId' => $this->request->get('address')
        ];

        $options = new Options(
            [
                'targetAddress' => $this->delivery->createAddress($address)
            ]
        );

        if ($this->delivery->points($code, $options)) {
            return $this->delivery->points($code, $options);
        } else {
            return [];
        }
    }
}
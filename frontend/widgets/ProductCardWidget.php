<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;


class ProductCardWidget extends Widget
{
    public int $id;
    public string $url;
    public float $price;
    public string $code;
    public string $articul;
    public string $name;
    public bool $isActive;

    public function run()
    {
        return $this->render('product-card/product-card', [
            'id'      => $this->id,
            'url'     => $this->url,
            'price'   => $this->prices(),
            'code'    => $this->code,
            'articul' => $this->articul,
            'name'    => $this->name,
            'isActive'=> $this->isActive,
            'isBookmark' => $this->isBookmark(),
            'images'  => $this->images(),
            'stickers'=> $this->stickers(),
        ]);
    }

    private function isBookmark(): bool 
    {
        if(!Yii::$app->user->id) {
            return false;
        }
        $sql = "SELECT count(f.id) FROM module_shop_favorite f WHERE (f.product_id = :product_id AND f.user_id = :user_id)";
        $params = [':product_id' => $this->id, 'user_id' => Yii::$app->user->id];
        return Yii::$app->db->createCommand($sql, $params)->queryScalar() > 0;
    }

    private function stickers(): array 
    {
        $sql = "SELECT msoi1.name, msoi1.code 
                FROM module_shop_products_multi_options mspmo
                LEFT JOIN module_shop_option_items msoi1 ON msoi1.id = mspmo.option_item_id  
                WHERE 
                    (mspmo.product_id = :product_id) AND 
                    (mspmo.option_id IN (
                        SELECT DISTINCT msoi2.option_id 
                        FROM module_shop_option_items msoi2 
                        WHERE 
                            msoi2.code IN('hit', 'new', 'discount')	
                    ))";

        return Yii::$app->db->createCommand($sql, [':product_id' => $this->id])->queryAll();
    }

    private function prices(): array 
    {
        $sql = "SELECT min(s.price) 
                FROM module_shop_sku s 
                WHERE 
                    (s.product_id = :product_id) AND 
                    (s.active = true) AND 
                    (s.remnants > 0) 
                GROUP BY s.product_id";

        $price = (float) Yii::$app->db->createCommand($sql, [':product_id' => $this->id])->queryScalar();

        return [
            'small_wholesale_price' => Yii::$app->prices->getSmallWholesalePrice($price),
            'wholesale_price' => Yii::$app->prices->getWholesalePrice($price),
            'price' => $price,
        ];
    }

    private function images(): array 
    {
        $images = [];
        if(!$this->id) {
            return $images;
        }

        $sql = "SELECT i.file 
                FROM module_shop_products_images pi 
                LEFT JOIN images i ON pi.image_id = i.id 
                WHERE pi.product_id = :product_id 
                ORDER BY pi.sort ASC";

        /** @var \frontend\components\ImageCacheComponent $imageCache */
        $imageCache = Yii::$app->get('imageCache');

        $noImageUrl = Yii::getAlias('@web/img/no-image.svg');
        $images = array_reduce(
            Yii::$app->db->createCommand($sql, [':product_id' => $this->id])->queryAll(),
            function($result, $image) use($imageCache, $noImageUrl) {
                if(isset($image['file'])) {
                    $src = Yii::getAlias('@webroot') . $image['file'];
                    $cachedName = $imageCache->resize($src, null, 575);
                    $url = $imageCache->relativePath($cachedName);    
                } else {
                    $url = $noImageUrl;
                }
                $result[] = [
                    'file' => $url
                ];
                return $result;
            }, []);
        if(!count($images)) {
            $images = [
                ['file' => $noImageUrl]
            ];
        }

        return $images;
    }
}
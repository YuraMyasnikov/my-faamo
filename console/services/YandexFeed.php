<?php

namespace console\services;

use cms\common\models\SeoPages;
use CmsModule\Shop\common\models\Categories;
use DateTimeImmutable;
use XMLWriter;
use Yii;
use yii\helpers\ArrayHelper;

class YandexFeed implements FeedInterface
{
    private $kind;
    public $domain;
    public $products;

    public function __construct()
    {
        $this->domain = Yii::$app->params['domain'];
        if ($this->domain[mb_strlen($this->domain) - 1] === '/') {
            $this->domain = mb_substr($this->domain, 0, -1);
        }
    }

    /**
     * @param array $products
     */
    public function withProducts(array $products): void
    {
        $this->products = $products;
    }

    public function export(string $file, string $source = null): bool
    {
        $this->kind = $source;
        $writer = new XMLWriter();
        $writer->openMemory();

        $writer->startDocument('1.0', 'UTF-8');
        $writer->startDtd('yml_catalog SYSTEM "shops.dtd"');
        $writer->endDtd();

        $writer->startElement('yml_catalog');
        $writer->writeAttribute('date', (new DateTimeImmutable('now'))->format('Y-m-d H:i'));
        $this->writeShop($writer);
        $writer->endElement();
        file_put_contents($file, $writer->outputMemory());
        return true;
    }

    private function writeShop($writer)
    {

        $seo = SeoPages::findOne(['url' => '/']);


        $writer->startElement('shop');
        $writer->writeElement('name', ($seo && !empty($seo->h1)) ? $seo->h1 : Yii::$app->name);


        $writer->writeElement('company', ($seo && !empty($seo->h1)) ? $seo->h1 : Yii::$app->name);
        $writer->writeElement('url', $this->domain);


        if ($this->kind !== 'webmaster') {
            $writer->startElement('currency');
            $writer->writeAttribute('id', 'RUB');
            $writer->writeAttribute('rate', '1');
            $writer->endElement();
        }

        $categories = Categories::find()
            ->where(['active' => 1])->all();

        $writer->startElement('categories');

        foreach ($categories as $category) {

            $writer->startElement('category');
            $writer->writeAttribute('id', (string)$category->id);

            if ($category->parent_id) {
                $writer->writeAttribute('parentId', $category->parent_id);
            }

            $writer->text($category->name);
            $writer->endElement();
            
        }
        $writer->endElement();

        $writer->startElement('offers');
        foreach ($this->products as $product) {
            $sizes = [];
            foreach ($product->sku as $sku) {
                $data = [
                    'price' => $sku->price,
                    'id' => 'offer' . $sku->id,
                    'groupId' => $product->id,
                    'size' => $sku->name,
                    'oldprice' => $sku->old_price,
                    'descr' => $sku->description,
                ];
                $this->writeProduct($writer, $product, $data);
            }
        }
        $writer->endElement();

        $writer->endElement();
    }

    public function writeProduct(XMLWriter $writer, $product, $data)
    {
        $url = '/product/' . $product->code;
        $seo = SeoPages::findOne(['url' => $url]);
        if ($seo && $seo->description) {
            $description = $seo->description;
        } else {
            $description = $product->description;
        }

        if ($description) {
            $description = sprintf('<p>%s</p>', $description);
        }

        $writer->startElement('offer');
        $writer->writeAttribute('available', 'true');
        if ($data['groupId']) {
            $writer->writeAttribute('group_id', (string)$data['groupId']);
        }
        $writer->writeAttribute('id', (string)$data['id']);

        $name = ($seo && !empty($seo->h1)) ? $seo->h1 : $product->name;
        $name = str_replace(' ' . $product->articul, '', $name);

        $writer->writeElement('name', $name);
        if ($description) {
            $writer->startElement('description');
            $writer->writeCdata($description);
            $writer->endElement();
        }
        $writer->writeElement('url', $this->domain . $url);
        $writer->writeElement('categoryId', (string)$product->mainCategory->id);
        $writer->writeElement('currencyId', 'RUB');

        $mainImage = Yii::$app->image->get($product->mainImage);
        if ($mainImage) {
            $writer->writeElement('picture', $this->domain . $mainImage->file);
        }

        $writer->writeElement('store', 'true');
        $writer->writeElement('model', ($seo && !empty($seo->h1)) ? $seo->h1 : $product->name);
        $writer->writeElement('delivery', 'true');
        $writer->writeElement('pickup', 'true');
        $writer->writeElement('manufacturer_warranty', 'true');

        $vendor = null;
        if (isset(Yii::$app->params['yandex-market']['vendor'])) {
            $vendor = Yii::$app->params['yandex-market']['vendor'];
        }
        /**
         * Product details
         */
        $params = $this->getProductDetails($product);
        if ($data['size']) {
            $params['Размер'] = [$data['size']];
        }
        foreach ($params as $label => $values) {
            $writer->startElement('param');
            $writer->writeAttribute('name', $label);
            $writer->text(implode(', ', $values));
            $writer->endElement();
        }

        $writer->writeElement('vendorCode', $product->articul);

        $writer->writeElement('price', (string)$data['price']);

        if ($data['oldprice'] > $data['price']) {
            $writer->writeElement('oldprice', (string)$data['oldprice']);
        }


        $writer->endElement();
    }

    protected function getProductDetails(
        $product
    ): array {
        $params = [];

        $productsOptions = $product->productsOptions;
        $productsMultiOptions = $product->productsMultiOptions;

        foreach (ArrayHelper::merge($productsOptions, $productsMultiOptions) as $productsOption) {
            $option = $productsOption->option;
            $optionItem = $productsOption->optionItem;

            $params[$option->name][] = $optionItem->name;
        }

        array_walk($params, function (&$item) {
            $item = array_unique($item);
        });

        return $params;
    }
}

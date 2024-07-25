<?php

namespace frontend\components;

use cms\common\models\SeoPages;
use cms\extensions\components\seo\Component as CmsSeoComponent;
use CmsModule\Shop\common\models\Categories;
use DateTime;
use frontend\models\shop\Products;
use SamDark\Sitemap\Index;
use SamDark\Sitemap\Sitemap;
use SamDark\Sitemap\Url;
use Yii;

class SeoComponent extends CmsSeoComponent
{
    public function generateSiteMap()
    {
        set_time_limit(0);

        $alias = Yii::getAlias('@frontend/web');

        $sitemap = new Sitemap($alias . '/sitemap-pages.xml');

        $index = new Index(Yii::getAlias($alias . '/sitemap.xml'));

        $domain = (Yii::$app->params['domain'] ?:
            ('http' . (Yii::$app->request->isSecureConnection ? 's' : '') . '://' .
            Yii::$app->request->getServerName()) . '/');
        $find = strlen($domain) - 1;

        if ($domain[$find] !== '/') {
            $domain .= '/';
        }
        /** Pages */

        $models = SeoPages::find()->where(['active' => true])->indexBy('url')->all();

        /** @var SeoPages $model */
        foreach ($models as $model) {
            $_url = $model->url[0] === '/' ? substr($model->url, 1) : $model->url;

            try {
                $pageUrl = $domain . $_url;

                $url = new Url($pageUrl);

                $url->setLastModified(new DateTime($model->updated_at));

                if (null !== $model->change_mode) {
                    $url->setChangeFrequency($model->change_mode);
                }

                $url->setPriority($model->priority);

                $sitemap->addUrl($url);
            } catch (\Exception $e) {
                Yii::error($e->getMessage(), 'sitemap');
            }
        }

        $products = Products::getQueryProductsActive()->all();

        foreach ($products as $product) {
            $productUrl = '/product/' . $product->code;

            if (!isset($models[$productUrl])) {
                $_url = 'product/' . $product->code;

                try {
                    $pageUrl = $domain . $_url;

                    $url = new Url($pageUrl);

                    $url->setLastModified(new DateTime($product->updated_at));

                    $url->setPriority(1);

                    $sitemap->addUrl($url);
                } catch (\Exception $e) {
                    Yii::error($e->getMessage(), 'sitemap');
                }
            }
        }

        $categories = Categories::find()
                            ->alias('category')
                            ->innerJoin(['product' => Products::tableName()], 'product.category_id = category.id')
                            ->where(['category.active' => true])
                            ->andWhere(['product.active' => true])
                            ->all();

        foreach ($categories as $category) {
            $categoryUrl = '/catalog/' . $category->code;

            if (!isset($models[$categoryUrl])) {
                $_url = 'catalog/' . $category->code;

                try {
                    $pageUrl = $domain . $_url;

                    $url = new Url($pageUrl);

                    $url->setLastModified(new DateTime($category->updated_at));

                    $url->setPriority(1);

                    $sitemap->addUrl($url);
                } catch (\Exception $e) {
                    Yii::error($e->getMessage(), 'sitemap');
                }
            }
        }

        $sitemap->write();

        /** Index */

        $siteMapUrls = $sitemap->getSitemapUrls($domain);
        foreach ($siteMapUrls as $url) {
            $index->addSitemap($url);
        }

        $index->write();
    }
}
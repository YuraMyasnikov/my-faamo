<?php

use yii\bootstrap5\BootstrapAsset;
use cms\extensions\components\seo\Component as CmsSeoComponent;
use frontend\components\SeoComponent;

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

$container = Yii::$container;

/**
 * Из-за этого класса BootstrapPluginAsset ехала верстка, где использовался DateTimePicker
 */
$container->set(yii\bootstrap\BootstrapPluginAsset::class, BootstrapAsset::class);

$container->set(CmsSeoComponent::class, SeoComponent::class);
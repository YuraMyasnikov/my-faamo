<?php 

namespace frontend\services;

use Yii;

class CityCodeResolver 
{
    public function getCode(): string 
    {
        return str_starts_with(Yii::$app->request->url, '/msk') ? 'msk' : 'spb';
    }

    public function getUrlPrefixForCurrentCity(): string 
    {
        return match(Yii::$app->session->get('city')) {
            'msk'   => '/msk',
            'spb'   => '',
            default => '',
        };
    }

    public function getCodeForCurrentCity(): string 
    {
        return match(Yii::$app->session->get('city')) {
            'msk'   => 'msk',
            'spb'   => 'spb',
            default => 'spb',
        };
    }
}
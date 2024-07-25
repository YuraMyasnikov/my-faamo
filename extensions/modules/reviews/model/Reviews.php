<?php
namespace CmsModule\Reviews\model;

use yii\db\ActiveRecord;

class Reviews extends ActiveRecord
{
    public static function tableName()
    {
        return '__iblock_content_reviews';
    }

}
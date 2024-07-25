<?php

namespace CmsModule\Blogs\frontend\forms;

use yii\base\Model;

class Blog extends Model
{
    public static function tableName()
    {
        return '__iblock_content_blog';
    }

    const TYPE = 'blog';

}
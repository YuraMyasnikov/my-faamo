<?php

namespace CmsModule\Infoblocks\models;

/**
 * @property integer $id
 * @property integer $site_id
 * @property integer $iblock_id
 * @property integer $active
 * @property integer $sort
 * @property string $name
 * @property string $code
 *
 * @property string $created_at
 * @property string $updated_at
 */
abstract class AbstractInfoblock implements InfoblockInterface
{
    public $id;
    public $site_id;
    public $iblock_id;
    public $active;
    public $sort;
    public $name;
    public $code;

    public $created_at;
    public $updated_at;
}

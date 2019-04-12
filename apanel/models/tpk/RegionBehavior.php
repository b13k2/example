<?php

namespace app\models\tpk;


class RegionBehavior extends \app\commands\core\JsonBehavior
{
    public function getLinkToJsonData()
    {
        return 'https://www.gpnbonus.ru/ios/v2/region.php';
    }

    public function getCacheFileName()
    {
        return 'tpk-region.txt';
    }

    public function getKeysMap()
    {
        return [
            'id'            => 'id',
            'name'          => 'name',
            'main_region'   => 'public_name',
            // 'sort'          => 'sorting',
        ];
    }
}


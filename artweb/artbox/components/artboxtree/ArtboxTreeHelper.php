<?php

namespace artweb\artbox\components\artboxtree;

use yii\base\Object;
use yii\helpers\ArrayHelper;

class ArtboxTreeHelper extends Object {
    public static function treeMap($tree, $from, $to, $symbol = '.')
    {
        $result = [];

        self::recursiveTreeMap($result, $tree, $from, $to, $symbol);

        return $result;
    }

    public static function setArrayField($path, $as_string = false) {
        if (is_array($path)) {
            if ($as_string) {
                foreach ($path as &$item) {
                    $item = "'$item'";
                }
            }
            $path = implode(',', $path);
        }
        return '{'. $path .'}';
    }

    public static function getArrayField($path) {
        $path = trim($path, '{}');
        return empty($path) ? [] : explode(',', $path);
    }

    protected static function recursiveTreeMap(&$result, $tree, $from, $to, $symbol = '&ndash;') {
        foreach ($tree as $item) {
            $element = $item['item'];
            $key = ArrayHelper::getValue($element, $from);
            $value = ArrayHelper::getValue($element, $to);
            $row = str_repeat($symbol, $element->depth+1) . $value;
            $result[$key] = $row;
            if (!empty($item['children'])) {
                self::recursiveTreeMap($result, $item['children'], $from, $to, $symbol);
            }
        }
    }
}
<?php
    
    namespace artweb\artbox\ecommerce\helpers;
    
    use yii\base\Object;
    
    class XmlHelper extends Object
    {
        public static function createElement(string $name, string $value = '', array $attributes = [])
        {
            $element = '<' . $name;
            foreach ($attributes as $key => $attribute) {
                $element .= ' ' . $key . '="' . $attribute . '"';
            }
            $element .= '>' . $value . '</' . $name . '>';
            return $element;
        }
    
        public static function createOpeningElement(string $name, array $attributes = [])
        {
            $element = '<' . $name;
            foreach ($attributes as $key => $attribute) {
                $element .= ' ' . $key . '="' . $attribute . '"';
            }
            $element .= '>';
            return $element;
        }
    
        public static function createClosingElement(string $name)
        {
            $element = '</' . $name . '>';
            return $element;
        }
    }
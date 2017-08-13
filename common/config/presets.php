<?php
    use yii\image\drivers\Kohana_Image;
    
    return [
        'slider_img'=>[
            'resize' => [
                'width'  => 848,
                'height' => 303,
                'master' => Kohana_Image::CROP,
            ],
        ],
        'event_list' =>[
            'resize' => [
                'width'  => 263,
                'height' => 210,
                'master' => null,
            ],
        ],
        'event_show' =>[
            'resize' => [
                'width'  => 1040,
            ],
        ],
        'product'        => [
            'resize' => [
                'width'  => 360,
                'height' => 360,
                'master' => null,
            ],
        ],
        'list'           => [
            'resize' => [
                'width'  => 360,
                'height' => 360,
            ],
        ],
        'card_thumb'     => [
            'resize' => [
                'width'  => 152,
                'height' => 108,
            ],
        ],
        'card_big'       => [
            'resize' => [
                'height' => 270,
            ],
        ],
        'category_item'  => [
            'resize' => [
                'height' => 186,
                'width'  => 220,
            ],
        ],
        'category_thumb' => [
            'resize' => [
                'height' => 40,
                'width'  => 40,
            ],
        ],
        'brand_main'     => [
            'resize' => [
                'height' => 50,
                'width'  => 80,
            ],
        ],
        'icon_category'  => [
            'resize' => [
                'height' => 40,
                'width'  => 70,
            ],
        ],
        'basket_item'    => [
            'resize' => [
                'width' => 165,
            ],
        ],
        'brand_list'  => [
            'resize' => [
                'height' => 85,
                'width'  => 133,
            ],
        ],
        'blog_list' => [
            'resize' => [
                'height' => 150,
                'width'  => 263,
            ],
        ],
    ];
        
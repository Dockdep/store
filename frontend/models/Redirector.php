<?php
    
    namespace frontend\models;
    
    use artweb\artbox\ecommerce\models\Product;
    use yii\helpers\Url;
    
    class Redirector
    {
        public static function processOld()
        {
            $path = \Yii::$app->request->pathInfo;
            if (preg_match('/^.*-(\d+)$/', $path, $matches) !== false) {
                if (isset( $matches[ 1 ] )) {
                    $remote_id = $matches[ 1 ];
                    /**
                     * @var Product $product
                     */
                    $product = Product::find()
                                      ->with('lang', 'variant')
                                      ->where([ 'remote_id' => $remote_id ])
                                      ->one();
                    if (!empty( $product )) {
                        $url = Url::to(
                            [
                                '/catalog/product',
                                'product' => $product->lang->alias,
                                'variant' => $product->variant->sku,
                            ],
                            true
                        );
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: ".$url);
                        die();
                    }
                }
            }
        }
    }
<?php
Yii::setAlias('@uploadDir', dirname(dirname(__DIR__)) . '/storage/sync');
Yii::setAlias('@uploadFileProducts', 'products.csv');
Yii::setAlias('@uploadFilePrices', 'file_1.csv');
Yii::setAlias('@uploadFilePricesAway', 'price_product_away.csv');
Yii::setAlias('@uploadFilePricesDuplicate', 'price_duplicate.csv');
Yii::setAlias('@uploadFilePricesNoVariant', 'price_no_variant.csv');

Yii::setAlias('@productsDir', '@storage/products');
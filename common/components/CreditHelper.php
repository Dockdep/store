<?php
    
    namespace common\components;
    
    class CreditHelper
    {
        
        const MAX_CREDIT_SUM = 25000;
        const MIN_CREDIT_SUM = 300;
        const MAX_MONTH_COUNT = 36;
        const MIN_MONTH_COUNT = 3;
        
        public static function getCredit($sum, $month = 36, $percent = 2)
        {
            $sum = self::checkSum($sum);
            $month = self::checkMonth($month);
            return ceil(($sum / $month) + ($sum * $percent / 100));
        }
        
        public static function checkSum($sum) {
            if($sum > self::MAX_CREDIT_SUM) {
                return self::MAX_CREDIT_SUM;
            } else {
                return $sum;
            }
        }
    
        public static function checkMonth($month) {
            if($month > self::MAX_MONTH_COUNT) {
                return self::MAX_MONTH_COUNT;
            } elseif($month < self::MIN_MONTH_COUNT) {
                return self::MIN_MONTH_COUNT;
            } else {
                return $month;
            }
        }
    }
    
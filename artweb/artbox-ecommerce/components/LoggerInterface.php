<?php
    namespace artweb\artbox\ecommerce\components;
    
    interface LoggerInterface
    {
        public static function generateData(array $changedAttributes, array $oldAttributes, bool $insert);
        
        public static function saveData(array $data, int $identityId, $params = []);
    }
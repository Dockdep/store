<?php
    namespace frontend\models;
    
    /**
     * Class for Credit form
     *
     * @inheritdoc
     */
    class OrderCredit extends OrderFrontend
    {
        public function scenarios()
        {
            return [
                self::SCENARIO_DEFAULT => [
                    'credit_sum',
                    'credit_month',
                ],
            ];
        }
        
        public function behaviors()
        {
            return [];
        }
        
        public function rules()
        {
            return [
                [
                    [ 'credit_month' ],
                    'integer',
                    'min' => 3,
                    'max' => 36,
                ],
                [
                    [ 'credit_sum' ],
                    'number',
                    'min' => 300,
                    'max' => 25000,
                ],
            ];
        }
    }
    
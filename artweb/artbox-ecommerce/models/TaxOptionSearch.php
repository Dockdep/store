<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * TaxOptionSearch represents the model behind the search form about
     * `artweb\artbox\ecommerce\models\TaxOption`.
     */
    class TaxOptionSearch extends TaxOption
    {
        
        public $value;
        
        public function behaviors()
        {
            $behaviors = parent::behaviors();
            if (isset( $behaviors[ 'language' ] )) {
                unset( $behaviors[ 'language' ] );
            }
            return $behaviors;
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'value' ],
                    'safe',
                ],
                [
                    [ 'id' ],
                    'integer',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }
        
        /**
         * Creates data provider instance with search query applied
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $query = TaxOption::find()
                              ->joinWith('lang');
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'value' => [
                                'asc'  => [ 'tax_option_lang.value' => SORT_ASC ],
                                'desc' => [ 'tax_option_lang.value' => SORT_DESC ],
                            ],
                        ],
                    ],
                ]
            );
            
            $this->load($params);
            
            //        if (!$this->validate()) {
            //            return $dataProvider;
            //        }
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    'id' => $this->id,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'tax_option_lang.value',
                          $this->value,
                      ]
                  );
            
            return $dataProvider;
        }
    }

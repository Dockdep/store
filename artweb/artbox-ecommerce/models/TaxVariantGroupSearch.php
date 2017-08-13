<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * TaxVariantGroupSearch represents the model behind the search form about
     * `artweb\artbox\ecommerce\models\TaxVariantGroup`.
     */
    class TaxVariantGroupSearch extends TaxVariantGroup
    {
        
        public $groupName;
        
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
                    [
                        'id',
                    ],
                    'integer',
                ],
                [
                    [
                        'is_filter',
                    ],
                    'boolean',
                ],
                [
                    [
                        'groupName',
                    ],
                    'safe',
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
            $query = TaxVariantGroup::find()
                             ->joinWith('lang');
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'is_filter',
                            'groupName' => [
                                'asc'  => [ 'tax_variant_group_lang.title' => SORT_ASC ],
                                'desc' => [ 'tax_variant_group_lang.title' => SORT_DESC ],
                            ],
                        ],
                    ],
                ]
            );
            
            $this->load($params);
            
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            $query->andFilterWhere(
                [
                    'id'        => $this->id,
                    'is_filter' => $this->is_filter,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'tax_variant_group_lang.title',
                          $this->groupName,
                      ]
                  );
            
            return $dataProvider;
        }
    }

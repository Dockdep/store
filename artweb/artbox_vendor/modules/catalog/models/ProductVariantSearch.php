<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * ProductVariantSearch represents the model behind the search form about
     * `artweb\artbox\modules\catalog\models\ProductVariant`.
     */
    class ProductVariantSearch extends ProductVariant
    {
        
        public $variantName;
        
        public function behaviors()
        {
            return [];
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'variantName',
                        'sku',
                    ],
                    'safe',
                ],
                [
                    [
                        'id',
                        'stock',
                    ],
                    'integer',
                ],
                [
                    [
                        'price',
                        'price_old',
                    ],
                    'number',
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
            $query = ProductVariant::find()
                                   ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                ]
            );
            
            $this->load($params);
            
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            $dataProvider->setSort(
                [
                    'attributes' => [
                        'id',
                        'sku',
                        'variantName' => [
                            'asc'  => [ 'product_variant_lang.title' => SORT_ASC ],
                            'desc' => [ 'product_variant_lang.title' => SORT_DESC ],
                        ],
                        'price',
                        'price_old',
                        'stock',
                    ],
                ]
            );
            
            $query->andFilterWhere(
                [
                    'price'     => $this->price,
                    'price_old' => $this->price_old,
                    'stock'     => $this->stock,
                ]
            );
            
            $query->andFilterWhere(
                [
                    'ilike',
                    'product_variant_lang.title',
                    $this->variantName,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'sku',
                          $this->sku,
                      ]
                  );
            
            $query->groupBy(
                [
                    'product_variant.id',
                    'product_variant_lang.title',
                ]
            );
            
            return $dataProvider;
        }
    }

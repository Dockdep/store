<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * BrandSearch represents the model behind the search form about
     * `artweb\artbox\ecommerce\models\Brand`.
     */
    class BrandSearch extends Brand
    {
        
        public $brandName;
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'brandName' ],
                    'safe',
                ],
                [
                    [ 'id' ],
                    'integer',
                ],
            ];
        }
        
        public function behaviors()
        {
            return [];
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
            $query = Brand::find()
                          ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                ]
            );
            
            $this->load($params);
            
            /*if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }*/
            
            $dataProvider->setSort(
                [
                    'attributes' => [
                        'id',
                        'brandName' => [
                            'asc'  => [ 'brand_lang.title' => SORT_ASC ],
                            'desc' => [ 'brand_lang.title' => SORT_DESC ],
                        ],
                    ],
                ]
            );
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    'brand.id' => $this->id,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'brand_lang.title',
                          $this->brandName,
                      ]
                  );
            
            return $dataProvider;
        }
    }

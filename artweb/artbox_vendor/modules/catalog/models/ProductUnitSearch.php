<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * ProductUnitSearch represents the model behind the search form about
     * `artweb\artbox\modules\catalog\models\ProductUnit`.
     */
    class ProductUnitSearch extends ProductUnit
    {
        
        public $title;
        
        public function behaviors()
        {
            return [];
        }
        
        public function attributeLabels()
        {
            $labels = parent::attributeLabels();
            $new_labels = [
                'title' => \Yii::t('product', 'Product Unit Name'),
            ];
            return array_merge($labels, $new_labels);
        }
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'title' ],
                    'safe',
                ],
                [
                    [ 'id' ],
                    'integer',
                ],
                [
                    [ 'is_default' ],
                    'boolean',
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
            $query = ProductUnit::find()
                                ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'is_defaut',
                            'title' => [
                                'asc'  => [ 'product_unit_lang.title' => SORT_ASC ],
                                'desc' => [ 'product_unit_lang.title' => SORT_DESC ],
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
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    'id'         => $this->id,
                    'is_default' => $this->is_default,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'product_unit_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

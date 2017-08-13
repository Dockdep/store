<?php
    
    namespace artweb\artbox\seo\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * SeoCategorySearch represents the model behind the search form about
     * `artweb\artbox\models\SeoCategory`.
     */
    class SeoCategorySearch extends SeoCategory
    {
        
        public $title;
        
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
                        'id',
                        'status',
                    ],
                    'integer',
                ],
                [
                    [
                        'controller',
                        'title',
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
            $query = SeoCategory::find()
                                ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'controller',
                            'status',
                            'title' => [
                                'asc'  => [ 'seo_category_lang.title' => SORT_ASC ],
                                'desc' => [ 'seo_category_lang.title' => SORT_DESC ],
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
                    'id'     => $this->id,
                    'status' => $this->status,
                ]
            );
            
            $query->andFilterWhere(
                [
                    'like',
                    'controller',
                    $this->controller,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'seo_category_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

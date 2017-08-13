<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * BannerSearch represents the model behind the search form about `artweb\artbox\models\Banner`.
     */
    class BannerSearch extends Banner
    {
        
        public $title;
        
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
                        'url',
                        'title',
                    ],
                    'safe',
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
            $query = Banner::find()
                           ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'url',
                            'status',
                            'title' => [
                                'asc'  => [ 'banner_lang.title' => SORT_ASC ],
                                'desc' => [ 'banner_lang.title' => SORT_DESC ],
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
                    'url',
                    $this->url,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'banner_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

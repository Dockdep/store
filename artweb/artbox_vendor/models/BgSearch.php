<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * BgSearch represents the model behind the search form about `artweb\artbox\models\Bg`.
     */
    class BgSearch extends Bg
    {
        
        public $title;
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'id' ],
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
            $query = Bg::find()
                       ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'url',
                            'title' => [
                                'asc'  => [ 'bg_lang.title' => SORT_ASC ],
                                'desc' => [ 'bg_lang.title' => SORT_DESC ],
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
                    'id' => $this->id,
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
                          'ilike',
                          'bg_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

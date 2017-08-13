<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * PageSearch represents the model behind the search form about `artweb\artbox\models\Page`.
     */
    class PageSearch extends Page
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
                    [ 'title' ],
                    'safe',
                ],
                [
                    [ 'in_menu' ],
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
        
        public function behaviors()
        {
            return [];
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
            $query = Page::find()
                         ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'title' => [
                                'asc'  => [ 'page_lang.title' => SORT_ASC ],
                                'desc' => [ 'page_lang.title' => SORT_DESC ],
                            ],
                            'id',
                            'in_menu',
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
                    'id'      => $this->id,
                    'in_menu' => $this->in_menu,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'page_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

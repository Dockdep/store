<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * ArticleSearch represents the model behind the search form about `artweb\artbox\models\Article`.
     */
    class ArticleSearch extends Article
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
                    'string',
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
            $query = Article::find()
                            ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort'  => [
                    'attributes' => [
                        'id',
                        'title' => [
                            'asc'  => [ 'article_lang.title' => SORT_ASC ],
                            'desc' => [ 'article_lang.title' => SORT_DESC ],
                        ],
                    ],
                ],
            ]);
            
            $this->load($params);
            
            if(!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            // grid filtering conditions
            $query->andFilterWhere([
                'id' => $this->id,
            ]);
            
            $query->andFilterWhere([
                'like',
                'article_lang.title',
                $this->title,
            ]);
            
            return $dataProvider;
        }
    }

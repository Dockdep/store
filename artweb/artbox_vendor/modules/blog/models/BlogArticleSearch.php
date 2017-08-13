<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * BlogArticleSearch represents the model behind the search form about `artweb\artbox\modules\blog\models\BlogArticle`.
     */
    class BlogArticleSearch extends BlogArticle
    {
        /**
         * @var string
         */
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
                        'deleted_at',
                        'sort',
                        'author_id',
                    ],
                    'integer',
                ],
                [
                    [ 'image' ],
                    'safe',
                ],
                [
                    [ 'status' ],
                    'boolean',
                ],
                [
                    [ 'title' ],
                    'string',
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
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
            $query = BlogArticle::find()
                                ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'created_at',
                            'updated_at',
                            'title' => [
                                'asc'  => [ 'blog_article_lang.title' => SORT_ASC ],
                                'desc' => [ 'blog_article_lang.title' => SORT_DESC ],
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
                    'id'        => $this->id,
                    'status'    => $this->status,
                    'author_id' => $this->author_id,
                ]
            );
            
            $query->andFilterWhere(
                [
                    'like',
                    'image',
                    $this->image,
                ]
            );
            
            $query->andFilterWhere(
                [
                    'like',
                    'blog_article_lang.title',
                    $this->title,
                ]
            );
            
            return $dataProvider;
        }
    }

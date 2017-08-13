<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * BlogCategorySearch represents the model behind the search form about `artweb\artbox\modules\blog\models\BlogCategory`.
     */
    class BlogCategorySearch extends BlogCategory
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
                        'sort',
                        'parent_id',
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
            $query = BlogCategory::find()
                                 ->joinWith('lang', 'parent.lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'title' => [
                                'asc'  => [ 'blog_category_lang.title' => SORT_ASC ],
                                'desc' => [ 'blog_category_lang.title' => SORT_DESC ],
                            ],
                            'id',
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
                    'sort'      => $this->sort,
                    'parent_id' => $this->parent_id,
                    'status'    => $this->status,
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
                    'blog_category_lang.title',
                    $this->title,
                ]
            );
            
            return $dataProvider;
        }
    }

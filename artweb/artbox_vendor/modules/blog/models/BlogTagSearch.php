<?php
    
    namespace artweb\artbox\modules\blog\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * BlogTagSearch represents the model behind the search form about `artweb\artbox\modules\blog\models\BlogTag`.
     */
    class BlogTagSearch extends BlogTag
    {
        /**
         * @var string
         */
        public $label;
        
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
                    [ 'label' ],
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
            $query = BlogTag::find()
                            ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'label' => [
                                'asc'  => [ 'blog_tag_lang.label' => SORT_ASC ],
                                'desc' => [ 'blog_tag_lang.label' => SORT_DESC ],
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
                    'blog_tag_lang.label',
                    $this->label,
                ]
            );
            
            return $dataProvider;
        }
    }

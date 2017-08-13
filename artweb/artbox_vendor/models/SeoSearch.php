<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * SeoSearch represents the model behind the search form about `artweb\artbox\models\Seo`.
     */
    class SeoSearch extends Seo
    {
        
        public $title;
        
        public $meta_description;
        
        public $h1;
        
        public $meta;
        
        public $seo_text;
        
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
                    [ 'id' ],
                    'integer',
                ],
                [
                    [
                        'url',
                        'title',
                        'meta_description',
                        'h1',
                        'meta',
                        'seo_text',
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
            $query = Seo::find()
                        ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'url',
                            'title'            => [
                                'asc'  => [ 'seo_lang.title' => SORT_ASC ],
                                'desc' => [ 'seo_lang.title' => SORT_DESC ],
                            ],
                            'meta_description' => [
                                'asc'  => [ 'seo_lang.meta_description' => SORT_ASC ],
                                'desc' => [ 'seo_lang.meta_description' => SORT_DESC ],
                            ],
                            'h1'               => [
                                'asc'  => [ 'seo_lang.h1' => SORT_ASC ],
                                'desc' => [ 'seo_lang.h1' => SORT_DESC ],
                            ],
                            'meta'             => [
                                'asc'  => [ 'seo_lang.meta' => SORT_ASC ],
                                'desc' => [ 'seo_lang.meta' => SORT_DESC ],
                            ],
                            'seo_text'         => [
                                'asc'  => [ 'seo_lang.seo_text' => SORT_ASC ],
                                'desc' => [ 'seo_lang.seo_text' => SORT_DESC ],
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
                          'seo_lang.title',
                          $this->title,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'seo_lang.meta_description',
                          $this->meta_description,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'seo_lang.h1',
                          $this->h1,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'seo_lang.meta',
                          $this->meta,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'seo_lang.seo_text',
                          $this->seo_text,
                      ]
                  );
            
            return $dataProvider;
        }
    }

<?php
    
    namespace artweb\artbox\seo\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * SeoDynamicSearch represents the model behind the search form about
     * `artweb\artbox\models\SeoDynamic`.
     */
    class SeoDynamicSearch extends SeoDynamic
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
                        'action',
                        'fields',
                        'title',
                        'param',
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
         * @param integer $seo_category_id
         * @param array   $params
         *
         * @return ActiveDataProvider
         */
        public function search($seo_category_id, $params)
        {
            $query = SeoDynamic::find()
                               ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'action',
                            'fields',
                            'status',
                            'param',
                            'title' => [
                                'asc'  => [ 'seo_dynamic_lang.title' => SORT_ASC ],
                                'desc' => [ 'seo_dynamic_lang.title' => SORT_DESC ],
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
            $query->andWhere(
                [
                    'seo_category_id' => $seo_category_id,
                ]
            )
                  ->andFilterWhere(
                      [
                          'id'     => $this->id,
                          'status' => $this->status,
                      ]
                  );
            
            $query->andFilterWhere(
                [
                    'ilike',
                    'action',
                    $this->action,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'fields',
                          $this->fields,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'param',
                          $this->param,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'seo_dynamic_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

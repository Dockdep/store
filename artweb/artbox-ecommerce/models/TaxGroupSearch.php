<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * TaxGroupSearch represents the model behind the search form about
     * `artweb\artbox\ecommerce\models\TaxGroup`.
     */
    class TaxGroupSearch extends TaxGroup
    {
        
        public $groupName;
        
        public $alias;
        
        public $description;
        
        public function behaviors()
        {
            $behaviors = parent::behaviors();
            if (isset( $behaviors[ 'language' ] )) {
                unset( $behaviors[ 'language' ] );
            }
            return $behaviors;
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
                        'level',
                        'sort',
                    ],
                    'integer',
                ],
                [
                    [
                        'is_filter',
                    ],
                    'boolean',
                ],
                [
                    [
                        'groupName',
                        'alias',
                        'description',
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
        public function search($params, int $level = NULL)
        {
            $query = TaxGroup::find()
                             ->joinWith('lang');
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'sort',
                            'is_filter',
                            'groupName'   => [
                                'asc'  => [ 'tax_group_lang.title' => SORT_ASC ],
                                'desc' => [ 'tax_group_lang.title' => SORT_DESC ],
                            ],
                            'alias'       => [
                                'asc'  => [ 'tax_group_lang.alias' => SORT_ASC ],
                                'desc' => [ 'tax_group_lang.alias' => SORT_DESC ],
                            ],
                            'description' => [
                                'asc'  => [ 'tax_group_lang.description' => SORT_ASC ],
                                'desc' => [ 'tax_group_lang.description' => SORT_DESC ],
                            ],
                        ],
                    ],
                ]
            );
            
            $this->load($params);
            
            if (!is_null($level)) {
                $this->level = $level;
            }
            
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            $query->andFilterWhere(
                [
                    'id'        => $this->id,
                    'is_filter' => $this->is_filter,
                    'level'     => $this->level,
                    'sort'      => $this->sort,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'tax_group_lang.title',
                          $this->groupName,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'tax_group_lang.alias',
                          $this->alias,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'tax_group_lang.description',
                          $this->description,
                      ]
                  );
            
            return $dataProvider;
        }
    }

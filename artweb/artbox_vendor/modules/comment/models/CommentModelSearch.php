<?php
    
    namespace artweb\artbox\modules\comment\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * CommentModelSearch represents the model behind the search form about
     * `artweb\artbox\modules\comment\models\CommentModel`.
     */
    class CommentModelSearch extends CommentModel
    {
        
        public $ratingValue;
        
        public $childrenCount;
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'artbox_comment_id',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'status',
                        'artbox_comment_pid',
                        'related_id',
                        'entity_id',
                    ],
                    'integer',
                ],
                [
                    [
                        'childrenCount',
                    ],
                    'integer',
                    'min' => 0,
                ],
                [
                    [
                        'ratingValue',
                    ],
                    'number',
                    'min' => 1,
                    'max' => 5,
                ],
                [
                    [
                        'user_id',
                        'text',
                        'username',
                        'email',
                        'ip',
                        'entity',
                        'info',
                    ],
                    'safe',
                ],
            ];
        }
        
        public function attributeLabels()
        {
            return array_merge(
                parent::attributeLabels(),
                [
                    'ratingValue'   => 'Рейтинг',
                    'childrenCount' => 'Количество ответов',
                ]
            );
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
            $query = CommentModel::find()
                                 ->joinWith(
                                     [
                                         'rating',
                                         'user',
                                     ]
                                 );
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes'   => [
                            'ratingValue' => [
                                'asc'  => [ 'artbox_comment_rating.value' => SORT_ASC ],
                                'desc' => [ 'artbox_comment_rating.value' => SORT_DESC ],
                            ],
                            'artbox_comment_id',
                            'date_add',
                            'text',
                            'user_id',
                            'status',
                            'entity',
                            'entity_id',
                        ],
                        'defaultOrder' => [
                            'created_at' => SORT_DESC,
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
                    'artbox_comment_id'     => $this->artbox_comment_id,
                    'created_at'            => $this->created_at,
                    'updated_at'            => $this->updated_at,
                    'deleted_at'            => $this->deleted_at,
                    'artbox_comment.status' => $this->status,
                    'artbox_comment_pid'    => $this->artbox_comment_pid,
                    'related_id'            => $this->related_id,
                    'entity_id'             => $this->entity_id,
                ]
            );
            
            $query->andFilterWhere(
                [
                    'like',
                    'text',
                    $this->text,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'username',
                          $this->username,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'like',
                          'email',
                          $this->email,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'like',
                          'ip',
                          $this->ip,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'like',
                          'entity',
                          $this->entity,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'like',
                          'info',
                          $this->info,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'artbox_comment_rating.value' => $this->ratingValue,
                      ]
                  );
            
            if (!empty( $this->user_id )) {
                $query->andWhere(
                    [
                        'or',
                        [ 'artbox_comment.user_id' => (int) $this->user_id ],
                        [
                            'like',
                            'user.username',
                            $this->user_id,
                        ],
                        [
                            'like',
                            'artbox_comment.username',
                            $this->user_id,
                        ],
                        [
                            'like',
                            'artbox_comment.email',
                            $this->user_id,
                        ],
                    ]
                );
            }
            
            return $dataProvider;
        }
    }

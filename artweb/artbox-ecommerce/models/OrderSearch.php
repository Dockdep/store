<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use yii\helpers\VarDumper;
    
    /**
     * OrderSearch represents the model behind the search form about `\artweb\artbox\ecommerce\models\Order`.
     */
    class OrderSearch extends Order
    {
        public $date_from;
        public $date_to;
        public $date_range;
        public $sku;
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'id',
                        'manager_id',
                    ],
                    'integer',
                ],
                [
                    [
                        'name',
                        'email',
                        'phone',
                        'date_from',
                        'date_to',
                        'date_range',
                        'created_at',
                        'body',
                        'declaration',
                        'consignment',
                        'delivery',
                        'label',
                        'sku',
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
            $query = Order::find()
                          ->where([ 'published' => true ]);
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query'      => $query,
                    'sort'       => [ 'defaultOrder' => [ 'id' => SORT_DESC ] ],
                    'pagination' => [
                        'pageSize' => 50,
                    ],
                ]
            );
            
            $this->load($params);
            
            //            VarDumper::dump($params, 10, true); die();
            
            if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }
            
            if (!empty($this->sku)) {
                $query->innerJoinWith('products.productVariant')
                      ->andWhere(
                          [
                              'product_variant.id' => $this->sku,
                          ]
                      );
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
                    'name',
                    $this->name,
                ]
            );
            $query->andFilterWhere(
                [
                    'like',
                    'email',
                    $this->email,
                ]
            );
            $query->andFilterWhere(
                [
                    'or',
                    [
                        'like',
                        'phone',
                        $this->phone,
                    ],
                    [
                        'like',
                        'phone2',
                        $this->phone,
                    ],
                ]
            );
            $query->andFilterWhere(
                [
                    'like',
                    'body',
                    $this->body,
                ]
            );
            $query->andFilterWhere(
                [
                    'like',
                    'consignment',
                    $this->consignment,
                ]
            );
            $query->andFilterWhere(
                [
                    'like',
                    'declaration',
                    $this->declaration,
                ]
            );
            $query->andFilterWhere(
                [
                    'label' => $this->label,
                
                ]
            );
            $query->andFilterWhere(
                [
                    'manager_id' => $this->manager_id,
                
                ]
            );
            $query->andFilterWhere(
                [
                    'delivery' => $this->delivery,
                ]
            );
            if (!empty($this->date_range)) {
                $this->date_from = strtotime(explode('to', $this->date_range)[ 0 ]);
                $this->date_to = strtotime(explode('to', $this->date_range)[ 1 ]);
                
                $query->andFilterWhere(
                    [
                        '>=',
                        'created_at',
                        $this->date_from,
                    ]
                );
                $query->andFilterWhere(
                    [
                        '<=',
                        'created_at',
                        $this->date_to,
                    ]
                );
            }
            
            return $dataProvider;
        }
        
        public function attributeLabels()
        {
            $labels = [
                'sku' => \Yii::t('app', 'Артикул'),
            ];
            
            return ArrayHelper::merge($labels, parent::attributeLabels());
        }
    }

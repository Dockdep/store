<?php
    
    namespace backend\models;
    
    use artweb\artbox\ecommerce\models\ProductVideo;
    use yii\data\ActiveDataProvider;
    
    class ProductVideoSearch extends ProductVideo
    {
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
                [
                    [
                        'is_main',
                        'is_display',
                    ],
                    'boolean',
                ],
            ];
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
            $query = ProductVideo::find();
            
            $query->where([ 'product_id' => null ]);
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'title',
                            'is_main',
                            'is_display',
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
                    'id'         => $this->id,
                    'is_main'    => $this->is_main,
                    'is_display' => $this->is_main,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }
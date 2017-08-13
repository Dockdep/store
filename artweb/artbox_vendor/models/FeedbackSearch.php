<?php
    
    namespace artweb\artbox\models;
    
    use yii\data\ActiveDataProvider;
    
    class FeedbackSearch extends Feedback
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
                    [
                        'name',
                        'phone',
                        'ip',
                        'created_at',
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
            return Feedback::scenarios();
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
            $query = Feedback::find();
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
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
                    'name',
                    $this->name,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'phone',
                          $this->phone,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'like',
                          'ip',
                          $this->ip,
                      ]
                  );
            
            return $dataProvider;
        }
    }

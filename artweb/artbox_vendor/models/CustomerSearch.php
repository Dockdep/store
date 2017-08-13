<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * CustomerSearch represents the model behind the search form about `artweb\artbox\models\Customer`.
     */
    class CustomerSearch extends Customer
    {
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'id',
                        'birth_day',
                        'birth_month',
                        'birth_year',
                        'group_id',
                    ],
                    'integer',
                ],
                [
                    [
                        'username',
                        'name',
                        'surname',
                        'phone',
                        'body',
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
            $query = Customer::find();
            
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
                    'id'          => $this->id,
                    'birth_day'   => $this->birth_day,
                    'birth_month' => $this->birth_month,
                    'birth_year'  => $this->birth_year,
                    'group_id'    => $this->group_id,
                ]
            );
            
            $query->andFilterWhere(
                [
                    'like',
                    'username',
                    $this->username,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'name',
                          $this->name,
                      ]
                  )
                  ->andFilterWhere(
                      [
                          'like',
                          'surname',
                          $this->surname,
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
                          'body',
                          $this->body,
                      ]
                  );
            
            return $dataProvider;
        }
    }

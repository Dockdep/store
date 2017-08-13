<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * EventSearch represents the model behind the search form about `artweb\artbox\models\Event`.
     */
    class EventSearch extends Event
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
                        'created_at',
                        'updated_at',
                        'end_at',
                    ],
                    'integer',
                ],
            ];
        }
        
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
            
            $query = Event::find();
            
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
                    'id'         => $this->id,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                    'end_at'     => $this->end_at,
                ]
            );
            
            return $dataProvider;
        }
    }

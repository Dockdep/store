<?php
    
    namespace artweb\artbox\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * DeliverySearch represents the model behind the search form about `artweb\artbox\models\Delivery`.
     */
    class DeliverySearch extends Delivery
    {
        
        /**
         * @var string
         */
        public $title;
        
        /**
         * @var string
         */
        public $parentTitle;
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [
                        'id',
                        'parent_id',
                        'value',
                        'sort',
                    ],
                    'integer',
                ],
                [
                    [ 'title' ],
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
            $query = Delivery::find()
                             ->joinWith('lang')
                             ->joinWith([ 'parent as parent' ]);
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'value',
                            'title' => [
                                'asc'  => [ 'order_delivery_lang.title' => SORT_ASC ],
                                'desc' => [ 'order_delivery_lang.title' => SORT_DESC ],
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
                    'id'    => $this->id,
                    'value' => $this->value,
                    'sort'  => $this->sort,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'order_delivery_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

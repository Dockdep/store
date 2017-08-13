<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * LabelSearch represents the model behind the search form about `artweb\artbox\ecommerce\models\Label`.
     */
    class LabelSearch extends Label
    {
        
        /**
         * @var string
         */
        public $title;
        
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
                        'label',
                        'title',
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
            $query = Label::find()
                          ->joinWith('lang');
            
            // add conditions that should always apply here
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => [
                        'attributes' => [
                            'id',
                            'label',
                            'title' => [
                                'asc'  => [ 'order_label_lang.title' => SORT_ASC ],
                                'desc' => [ 'order_label_lang.title' => SORT_DESC ],
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
                    'label',
                    $this->label,
                ]
            )
                  ->andFilterWhere(
                      [
                          'like',
                          'order_label_lang.title',
                          $this->title,
                      ]
                  );
            
            return $dataProvider;
        }
    }

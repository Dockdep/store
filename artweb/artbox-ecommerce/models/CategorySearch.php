<?php
    
    namespace artweb\artbox\ecommerce\models;
    
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    
    /**
     * CategorySearch represents the model behind the search form about
     * `artweb\artbox\ecommerce\models\Category`.
     */
    class CategorySearch extends Category
    {
        
        public $categoryName;
        
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
                    [ 'categoryName' ],
                    'safe',
                ],
                [
                    [ 'id' ],
                    'integer',
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
            $query = Category::find()
                             ->joinWith('lang');
            
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => $query,
                    'sort'  => false,
                ]
            );
            
            $this->load($params);
            
            /*if (!$this->validate()) {
                // uncomment the following line if you do not want to return any records when validation fails
                // $query->where('0=1');
                return $dataProvider;
            }*/
            
            // grid filtering conditions
            $query->andFilterWhere(
                [
                    'category.id' => $this->id,
                ]
            )
                  ->andFilterWhere(
                      [
                          'ilike',
                          'category_lang.title',
                          $this->categoryName,
                      ]
                  );
            
            $query->orderBy(
                [
                    'category.path'  => SORT_ASC,
                    'category.depth' => SORT_ASC,
                    'category.id'    => SORT_ASC,
                ]
            );
            
            return $dataProvider;
        }
    }

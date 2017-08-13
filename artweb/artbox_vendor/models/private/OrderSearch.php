<?php

namespace artweb\artbox\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `artweb\artbox\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'label', 'pay', 'numbercard'], 'integer'],
            [['name', 'phone', 'phone2', 'email', 'adress', 'body', 'date_time', 'date_dedline', 'reserve', 'status', 'comment', 'delivery', 'declaration', 'stock', 'consignment', 'payment', 'insurance', 'shipping_by', 'city'], 'safe'],
            [['total', 'amount_imposed'], 'number'],
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
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total' => $this->total,
            'date_time' => $this->date_time,
            'date_dedline' => $this->date_dedline,
            'label' => $this->label,
            'pay' => $this->pay,
            'numbercard' => $this->numbercard,
            'amount_imposed' => $this->amount_imposed,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'phone2', $this->phone2])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'adress', $this->adress])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'reserve', $this->reserve])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'delivery', $this->delivery])
            ->andFilterWhere(['like', 'declaration', $this->declaration])
            ->andFilterWhere(['like', 'stock', $this->stock])
            ->andFilterWhere(['like', 'consignment', $this->consignment])
            ->andFilterWhere(['like', 'payment', $this->payment])
            ->andFilterWhere(['like', 'insurance', $this->insurance])
            ->andFilterWhere(['like', 'shipping_by', $this->shipping_by])
            ->andFilterWhere(['like', 'city', $this->city]);

        return $dataProvider;
    }
}

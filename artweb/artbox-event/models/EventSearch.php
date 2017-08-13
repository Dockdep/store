<?php

namespace artweb\artbox\event\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form about `common\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'end_at'], 'integer'],
            [['name', 'alias', 'body', 'image', 'meta_title', 'description', 'h1', 'seo_text'], 'safe'],
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


        $query = Event::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('lang');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'end_at' => $this->end_at,
        ]);

       if($this->lang !== null){
           $query->andFilterWhere(['like', 'title', $this->lang->title])
               ->andFilterWhere(['like', 'alias', $this->lang->alias])
               ->andFilterWhere(['like', 'body', $this->lang->body])
               ->andFilterWhere(['like', 'image', $this->image])
               ->andFilterWhere(['like', 'meta_title', $this->lang->meta_title])
               ->andFilterWhere(['like', 'description', $this->lang->meta_description])
               ->andFilterWhere(['like', 'h1', $this->lang->h1])
               ->andFilterWhere(['like', 'seo_text', $this->lang->seo_text]);
       }


        return $dataProvider;
    }
}

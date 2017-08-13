<?php

namespace artweb\artbox\seo\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use artweb\artbox\seo\models\Seo;

/**
 * SeoSearch represents the model behind the search form about `common\models\Seo`.
 */
class SeoSearch extends Seo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['url', 'title', 'meta', 'description', 'h1', 'seo_text'], 'safe'],
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
        $query = Seo::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta', $this->meta])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'h1', $this->h1])
            ->andFilterWhere(['like', 'seo_text', $this->seo_text]);

        return $dataProvider;
    }
}

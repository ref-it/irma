<?php

namespace app\models\db\search;

use app\models\db\Gremium;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GremienSearch represents the model behind the search form of `app\models\Gremien`.
 */
class GremiumSearch extends Gremium
{
    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['id', 'parentGremium'], 'integer'],
            [['name', 'belongingRealm'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Gremium::find();

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
            'parentGremium' => $this->parentGremium,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'belongingRealm', $this->belongingRealm]);

        return $dataProvider;
    }
}

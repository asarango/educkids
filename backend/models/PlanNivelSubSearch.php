<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanNivelSub;

/**
 * PlanNivelSubSearch represents the model behind the search form of `backend\models\PlanNivelSub`.
 */
class PlanNivelSubSearch extends PlanNivelSub
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curso_template_id', 'nivel_id'], 'integer'],
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
    public function search($params, $id)
    {
        $query = PlanNivelSub::find()
                ->where(['nivel_id' => $id]);

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
            'curso_template_id' => $this->curso_template_id,
            'nivel_id' => $this->nivel_id,
        ]);

        return $dataProvider;
    }
}

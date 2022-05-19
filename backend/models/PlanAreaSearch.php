<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlanArea;

/**
 * PlanAreaSearch represents the model behind the search form of `backend\models\PlanArea`.
 */
class PlanAreaSearch extends PlanArea
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','area_id'], 'integer'],
            [['nombre','tipo_area', 'color'], 'safe'],
            [['en_ministerio'], 'boolean'],
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
        $query = PlanArea::find();

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
            'en_ministerio' => $this->en_ministerio,
            'area_id' => $this->area_id,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
                //->andFilterWhere(['ilike', 'tipo_area', $this->tipo_area]);
                ->andFilterWhere(['ilike', 'color', $this->color]);
        return $dataProvider;
    }
}

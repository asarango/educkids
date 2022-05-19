<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisFirmasReportes;

/**
 * ScholarisFirmasReportesSearch represents the model behind the search form of `backend\models\ScholarisFirmasReportes`.
 */
class ScholarisFirmasReportesSearch extends ScholarisFirmasReportes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'template_id','instituto_id'], 'integer'],
            [['codigo_reporte', 'principal_cargo', 'principal_nombre', 'secretaria_cargo', 'secretaria_nombre'], 'safe'],
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
    public function search($params, $institutoId)
    {
        $query = ScholarisFirmasReportes::find()
                ->where(['instituto_id'=>$institutoId]);

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
            'template_id' => $this->template_id,
            'instituto_id' => $this->instituto_id,
        ]);

        $query->andFilterWhere(['ilike', 'codigo_reporte', $this->codigo_reporte])
            ->andFilterWhere(['ilike', 'principal_cargo', $this->principal_cargo])
            ->andFilterWhere(['ilike', 'principal_nombre', $this->principal_nombre])
            ->andFilterWhere(['ilike', 'secretaria_cargo', $this->secretaria_cargo])
            ->andFilterWhere(['ilike', 'secretaria_nombre', $this->secretaria_nombre]);

        return $dataProvider;
    }
}

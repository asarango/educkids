<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisLeccionario;

/**
 * ScholarisLeccionarioSearch represents the model behind the search form of `backend\models\ScholarisLeccionario`.
 */
class ScholarisLeccionarioSearch extends ScholarisLeccionario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paralelo_id', 'total_clases', 'total_revisadas'], 'integer'],
            [['fecha', 'usuario_crea', 'fecha_crea', 'usuario_actualiza', 'fecha_actualiza'], 'safe'],
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
    public function search($params,$paralelo)
    {
        $query = ScholarisLeccionario::find()
                ->where(['paralelo_id' => $paralelo])
                ->orderBy(["fecha" => SORT_DESC]);

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
            'paralelo_id' => $this->paralelo_id,
            'fecha' => $this->fecha,
            'total_clases' => $this->total_clases,
            'total_revisadas' => $this->total_revisadas,
            'fecha_crea' => $this->fecha_crea,
            'fecha_actualiza' => $this->fecha_actualiza,
        ]);

        $query->andFilterWhere(['ilike', 'usuario_crea', $this->usuario_crea])
            ->andFilterWhere(['ilike', 'usuario_actualiza', $this->usuario_actualiza]);

        return $dataProvider;
    }
}

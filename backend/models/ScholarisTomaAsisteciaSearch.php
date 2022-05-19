<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisTomaAsistecia;

/**
 * ScholarisTomaAsisteciaSearch represents the model behind the search form of `backend\models\ScholarisTomaAsistecia`.
 */
class ScholarisTomaAsisteciaSearch extends ScholarisTomaAsistecia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'paralelo_id', 'bloque_id'], 'integer'],
            [['fecha', 'observacion', 'creado_por', 'creado_fecha', 'actualizado_por', 'actualizado_fecha'], 'safe'],
            [['hubo_clases'], 'boolean'],
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
    public function search($params, $paralelo)
    {
        $query = ScholarisTomaAsistecia::find()
               ->where(['paralelo_id' => $paralelo])
                ->orderBy(['fecha' => SORT_DESC]);

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
            'paralelo_id' => $this->paralelo_id,
            'fecha' => $this->fecha,
            'bloque_id' => $this->bloque_id,
            'hubo_clases' => $this->hubo_clases,
            'creado_fecha' => $this->creado_fecha,
            'actualizado_fecha' => $this->actualizado_fecha,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion])
            ->andFilterWhere(['ilike', 'creado_por', $this->creado_por])
            ->andFilterWhere(['ilike', 'actualizado_por', $this->actualizado_por]);

        return $dataProvider;
    }
}

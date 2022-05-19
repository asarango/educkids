<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMecV2Homologacion;

/**
 * ScholarisMecV2HomologacionSearch represents the model behind the search form of `backend\models\ScholarisMecV2Homologacion`.
 */
class ScholarisMecV2HomologacionSearch extends ScholarisMecV2Homologacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'distribucion_id', 'codigo_tipo'], 'integer'],
            [['tipo', 'nombre_tipo', 'profesor_nombre'], 'safe'],
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
    public function search($params, $distribucion)
    {
        $query = ScholarisMecV2Homologacion::find()
                ->where(['distribucion_id' => $distribucion]);

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
            'distribucion_id' => $this->distribucion_id,
            'codigo_tipo' => $this->codigo_tipo,
        ]);

        $query->andFilterWhere(['ilike', 'tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'nombre_tipo', $this->nombre_tipo])
            ->andFilterWhere(['ilike', 'profesor_nombre', $this->profesor_nombre]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisArea;

/**
 * ScholarisAreaSearch represents the model behind the search form of `backend\models\ScholarisArea`.
 */
class ScholarisAreaSearch extends ScholarisArea
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'write_uid', 'idcategoriamateria', 'section_id', 'promedia', 'ministeriable', 'orden'], 'integer'],
            [['create_date', 'name', 'write_date', 'period_id', 'estado_codigo', 'codigo', 'nombre_mec'], 'safe'],
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
        $query = ScholarisArea::find()
                ->orderBy(['period_id' =>SORT_DESC]);

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
            'create_uid' => $this->create_uid,
            'create_date' => $this->create_date,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
            'idcategoriamateria' => $this->idcategoriamateria,
            'section_id' => $this->section_id,
            'promedia' => $this->promedia,
            'ministeriable' => $this->ministeriable,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'period_id', $this->period_id])
            ->andFilterWhere(['ilike', 'estado_codigo', $this->estado_codigo])
            ->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'nombre_mec', $this->nombre_mec]);

        return $dataProvider;
    }
}

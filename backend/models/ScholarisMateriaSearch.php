<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisMateria;

/**
 * ScholarisMateriaSearch represents the model behind the search form of `backend\models\ScholarisMateria`.
 */
class ScholarisMateriaSearch extends ScholarisMateria
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'create_uid', 'write_uid', 'area_id', 'tipo_materia_id', 'orden', 'promedia'], 'integer'],
            [['create_date', 'name', 'write_date', 'color', 'tipo', 'nombre_mec', 'abreviarura','is_active', 'language_code'], 'safe'],
            [['peso'], 'number'],
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
    public function search($params, $periodo, $ultimoPeriodo)
    {
        $query = ScholarisMateria::find()
               ->innerJoin("scholaris_area a", "a.id = scholaris_materia.area_id")
               ->where(["a.period_id" => $ultimoPeriodo]);

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
            'area_id' => $this->area_id,
            'tipo_materia_id' => $this->tipo_materia_id,
            'peso' => $this->peso,
            'orden' => $this->orden,
            'promedia' => $this->promedia,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['ilike', 'scholaris_materia.name', $this->name])
            ->andFilterWhere(['ilike', 'scholaris_materia.color', $this->color])
            ->andFilterWhere(['ilike', 'scholaris_materia.tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'scholaris_materia.abreviarura', $this->abreviarura])
            ->andFilterWhere(['ilike', 'scholaris_materia.language_code', $this->language_code])
            ->andFilterWhere(['ilike', 'nombre_mec', $this->nombre_mec]);

        return $dataProvider;
    }
}

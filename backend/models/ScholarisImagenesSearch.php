<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisImagenes;

/**
 * ScholarisImagenesSearch represents the model behind the search form of `backend\models\ScholarisImagenes`.
 */
class ScholarisImagenesSearch extends ScholarisImagenes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'alto_pixeles', 'ancho_pixeles'], 'integer'],
            [['codigo', 'nombre_archivo', 'detalle'], 'safe'],
            [['imagen_educandi'], 'boolean'],
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
        $query = ScholarisImagenes::find();

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
            'alto_pixeles' => $this->alto_pixeles,
            'ancho_pixeles' => $this->ancho_pixeles,
            'imagen_educandi' => $this->imagen_educandi,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'nombre_archivo', $this->nombre_archivo])
            ->andFilterWhere(['ilike', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}

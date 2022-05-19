<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisInstitutoAutoridades;

/**
 * ScholarisInstitutoAutoridadesSearch represents the model behind the search form of `backend\models\ScholarisInstitutoAutoridades`.
 */
class ScholarisInstitutoAutoridadesSearch extends ScholarisInstitutoAutoridades
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'periodo_id', 'instituto_id'], 'integer'],
            [['titulo', 'cargo', 'nombre', 'tipo_autoridad'], 'safe'],
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
    public function search($params, $id, $periodoId)
    {
        $query = ScholarisInstitutoAutoridades::find()
                ->where(['periodo_id' => $periodoId, 'instituto_id' => $id]);

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
            'periodo_id' => $this->periodo_id,
            'instituto_id' => $this->instituto_id,
        ]);

        $query->andFilterWhere(['ilike', 'titulo', $this->titulo])
            ->andFilterWhere(['ilike', 'cargo', $this->cargo])
            ->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'tipo_autoridad', $this->tipo_autoridad]);

        return $dataProvider;
    }
}

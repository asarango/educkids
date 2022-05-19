<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisRepLibreta;

/**
 * ScholarisRepLibretaSearch represents the model behind the search form of `backend\models\ScholarisRepLibreta`.
 */
class ScholarisRepLibretaSearch extends ScholarisRepLibreta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'usuario', 'tipo', 'asignatura','tipo_calificacion'], 'safe'],
            [['clase_id', 'promedia', 'tipo_uso_bloque', 'asignatura_id', 'paralelo_id', 'alumno_id', 'area_id'], 'integer'],
            [['p1', 'p2', 'p3', 'pr1', 'ex1', 'pr180', 'ex120', 'q1', 'p4', 'p5', 'p6', 'pr2', 'ex2', 'pr280', 'ex220', 'q2', 'nota_final', 'peso'], 'number'],
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
    public function search($params, $usuario, $paralelo)
    {
        $query = ScholarisRepLibreta::find()
                ->where(['usuario' => $usuario, 'paralelo_id' => $paralelo]);

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
            'clase_id' => $this->clase_id,
            'promedia' => $this->promedia,
            'peso' => $this->peso,
            'tipo_uso_bloque' => $this->tipo_uso_bloque,
            'asignatura_id' => $this->asignatura_id,
            'paralelo_id' => $this->paralelo_id,
            'alumno_id' => $this->alumno_id,
            'area_id' => $this->area_id,
            'p1' => $this->p1,
            'p2' => $this->p2,
            'p3' => $this->p3,
            'pr1' => $this->pr1,
            'ex1' => $this->ex1,
            'pr180' => $this->pr180,
            'ex120' => $this->ex120,
            'q1' => $this->q1,
            'p4' => $this->p4,
            'p5' => $this->p5,
            'p6' => $this->p6,
            'pr2' => $this->pr2,
            'ex2' => $this->ex2,
            'pr280' => $this->pr280,
            'ex220' => $this->ex220,
            'q2' => $this->q2,
            'nota_final' => $this->nota_final,
        ]);

        $query->andFilterWhere(['ilike', 'codigo', $this->codigo])
            ->andFilterWhere(['ilike', 'usuario', $this->usuario])
            ->andFilterWhere(['ilike', 'tipo', $this->tipo])
            ->andFilterWhere(['ilike', 'tipo_calificacion', $this->tipo_calificacion])
            ->andFilterWhere(['ilike', 'asignatura', $this->asignatura]);

        return $dataProvider;
    }
    
}

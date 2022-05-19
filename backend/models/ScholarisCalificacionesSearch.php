<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisCalificaciones;

/**
 * ScholarisCalificacionesSearch represents the model behind the search form of `backend\models\ScholarisCalificaciones`.
 */
class ScholarisCalificacionesSearch extends ScholarisCalificaciones
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idalumno', 'idactividad', 'idtipoactividad', 'idperiodo', 'criterio_id', 'estado_proceso', 'grupo_numero', 'estado'], 'integer'],
            [['calificacion'], 'number'],
            [['observacion'], 'safe'],
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
    public function search($params, $peridoCodigo)
    {
        $query = ScholarisCalificaciones::find()
                ->innerJoin("scholaris_actividad", "scholaris_actividad.id = scholaris_calificaciones.idactividad")
                ->innerJoin("scholaris_clase","scholaris_clase.id = scholaris_actividad.paralelo_id")
                ->where(['scholaris_clase.periodo_scholaris' => $peridoCodigo]);

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
            'idalumno' => $this->idalumno,
            'idactividad' => $this->idactividad,
            'idtipoactividad' => $this->idtipoactividad,
            'idperiodo' => $this->idperiodo,
            'calificacion' => $this->calificacion,
            'criterio_id' => $this->criterio_id,
            'estado_proceso' => $this->estado_proceso,
            'grupo_numero' => $this->grupo_numero,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['ilike', 'observacion', $this->observacion]);

        return $dataProvider;
    }
    
    
}

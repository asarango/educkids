<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ScholarisIntitutoDatosGenerales;

/**
 * ScholarisIntitutoDatosGeneralesSearch represents the model behind the search form of `backend\models\ScholarisIntitutoDatosGenerales`.
 */
class ScholarisIntitutoDatosGeneralesSearch extends ScholarisIntitutoDatosGenerales
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'instituto_id', 'ejecucion_desde', 'ejecucion_hasta'], 'integer'],
            [['direccion', 'codigo_amie', 'telefono', 'provincia', 'canton', 'parroquia', 'correo', 'sitio_web', 'sostenimiento', 'regimen', 'modalidad', 'niveles_curriculares', 'subniveles', 'distrito', 'circuito', 'jornada', 'horario_trabajo', 'local', 'genero', 'financiamiento'], 'safe'],
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
    public function search($params, $id)
    {
        $query = ScholarisIntitutoDatosGenerales::find()
                ->where(['instituto_id' => $id]);

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
            'instituto_id' => $this->instituto_id,
            'ejecucion_desde' => $this->ejecucion_desde,
            'ejecucion_hasta' => $this->ejecucion_hasta,
        ]);

        $query->andFilterWhere(['ilike', 'direccion', $this->direccion])
            ->andFilterWhere(['ilike', 'codigo_amie', $this->codigo_amie])
            ->andFilterWhere(['ilike', 'telefono', $this->telefono])
            ->andFilterWhere(['ilike', 'provincia', $this->provincia])
            ->andFilterWhere(['ilike', 'canton', $this->canton])
            ->andFilterWhere(['ilike', 'parroquia', $this->parroquia])
            ->andFilterWhere(['ilike', 'correo', $this->correo])
            ->andFilterWhere(['ilike', 'sitio_web', $this->sitio_web])
            ->andFilterWhere(['ilike', 'sostenimiento', $this->sostenimiento])
            ->andFilterWhere(['ilike', 'regimen', $this->regimen])
            ->andFilterWhere(['ilike', 'modalidad', $this->modalidad])
            ->andFilterWhere(['ilike', 'niveles_curriculares', $this->niveles_curriculares])
            ->andFilterWhere(['ilike', 'subniveles', $this->subniveles])
            ->andFilterWhere(['ilike', 'distrito', $this->distrito])
            ->andFilterWhere(['ilike', 'circuito', $this->circuito])
            ->andFilterWhere(['ilike', 'jornada', $this->jornada])
            ->andFilterWhere(['ilike', 'horario_trabajo', $this->horario_trabajo])
            ->andFilterWhere(['ilike', 'local', $this->local])
            ->andFilterWhere(['ilike', 'genero', $this->genero])
            ->andFilterWhere(['ilike', 'financiamiento', $this->financiamiento]);

        return $dataProvider;
    }
}

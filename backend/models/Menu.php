<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nombre', 'orden', 'icono'], 'required'],
            [['orden'], 'default', 'value' => null],
            [['orden'], 'integer'],
            [['codigo'], 'string', 'max' => 30],
            [['nombre'], 'string', 'max' => 50],
            [['icono'], 'string', 'max' => 150],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
            'orden' => 'Orden',
            'icono' => 'Ícono',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperacions()
    {
        return $this->hasMany(Operacion::className(), ['menu_id' => 'id']);
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function getMenus($usuario)
    {
        $con = Yii::$app->db;
        $query = "select m.id
                         ,m.nombre
                         ,m.icono
                from	operacion o
                                inner join menu m on m.id = o.menu_id
                                inner join rol_operacion ro on ro.operacion_id = o.id
                                inner join rol r on r.id = ro.rol_id
                                inner join usuario u on u.rol_id = r.id
                where	u.usuario = '$usuario'
                group by m.id, m.nombre 
                order by orden asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOperaciones($menu)
    {
        $con = Yii::$app->db;
        $query = "select 	o.id
                                ,o.nombre
                                ,o.operacion
                from	operacion o
                                inner join menu m on m.id = o.menu_id
                                inner join rol_operacion ro on ro.operacion_id = o.id
                                inner join rol r on r.id = ro.rol_id
                                inner join usuario u on u.rol_id = r.id
                where	m.id = $menu
                                and o.operacion ilike '%-index'
                group by o.id
                                ,o.nombre;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
}

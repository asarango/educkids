<?php

namespace backend\models;
use yii\base\Model;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "cur_curriculo".
 *
 * @property int $id
 * @property int $materia_id
 * @property string $tipo_referencia
 * @property string $codigo
 * @property string $detalle
 * @property bool $imprencindible
 * @property int $bloque_id
 * @property string $campo_aux1
 * @property string $campo_aux2
 * @property string $pertence_a
 *
 * @property GenMallaMateria $materia
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, jpg, png',
            'maxSize' => 1024*1024*10],'maxFile'=>4
        ];
    }
    
    public function upload($nombre)
    {
        if ($this->validate()) {
           //$this->imageFile->saveAs('imagenes/pud/' . $nombre);
            foreach ($this->imageFiles as $file) {
                //$file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                $file->saveAs('imagenes/pud/' . $file->baseName . '.' . $file->extension);
            }
            return true;
        } else {
            return false;
        }
    }
}

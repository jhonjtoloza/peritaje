<?php

namespace app\models;

use yii\helpers\Json;

/**
 * This is the model class for table "rellenado".
 *
 * @property int $id
 * @property string $columna
 * @property string $espacio
 * @property int $opciones
 * @property string $val_opciones
 * @property string $label
 * @property int $seccion_id
 * @property string $type
 */
class Rellenado extends \yii\db\ActiveRecord
{
  /**
   * @inheritdoc
   */
  public static function tableName()
  {
    return 'rellenado';
  }

  /**
   * @inheritdoc
   */
  public function rules()
  {
    return [
      [['columna', 'opciones', 'label','type'], 'required'],
      [['opciones'], 'validateOpciones'],
      [['seccion_id'], 'integer'],
      [['columna', 'label'], 'string', 'max' => 100],
      [['espacio'], 'string', 'max' => 5],
      [['val_opciones'], 'string', 'max' => 500],
      [['columna'], 'unique'],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'id' => 'ID',
      'columna' => 'Columna',
      'espacio' => 'Espacio',
      'opciones' => 'Opciones',
      'val_opciones' => 'Val Opciones',
      'label' => 'Label',
      'seccion_id' => 'Seccion ID',
    ];
  }

  public function validateOpciones()
  {
    if ($this->opciones=='1' and $this->val_opciones == '') {
      $this->addError('opciones', 'Opciones es requerido');
    } else if ($this->opciones=='1' and $this->espacio == '') {
      $this->addError('espacio', 'Espacio es requerido');
    }
  }

  public function beforeValidate()
  {
    if (is_array($this->val_opciones)) {
      $ops = [];
      foreach ($this->val_opciones as $opcion) {
        $ops[] = [
          'opcion' => strtoupper($opcion['opcion']),
          'espacio' => strtoupper($opcion['espacio']),
          'marca' => strtoupper($opcion['marca'])
        ];
      }
      $this->val_opciones = Json::encode($ops);
    }
    $this->espacio = strtoupper($this->espacio);
    $this->label = ucwords($this->label);
    return parent::beforeValidate();
  }
}

<?php
/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 29/05/18
 * Time: 12:35 PM
 */

namespace app\models;


use yii\base\Model;

class FileUpload extends Model
{
  public $picture;

  public function rules()
  {
    return [
      [['picture'],'file','skipOnEmpty' => false]
    ];
  }

  public function attributeLabels()
  {
    return [
      'picture'=>'Imagen para el logo'
    ];
  }
}
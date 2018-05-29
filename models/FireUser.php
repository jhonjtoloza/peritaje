<?php
/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 9/05/18
 * Time: 10:06 AM
 */

namespace app\models;


use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use yii\base\Model;

class FireUser extends Model
{
  public $uid;
  public $displayName;
  public $email;
  public $phoneNumber;
  public $password;

  public $emailVerified = true;
  public $disabled = false;

  public function rules()
  {
    return [
      [['displayName', 'email', 'phoneNumber', 'password'], 'required', 'on' => 'create'],
      [['displayName', 'email', 'phoneNumber'], 'required', 'on' => 'update'],
      [['password'], 'string', 'min' => 6, 'max' => 20],
      [['phoneNumber'],'phoneValidate'],
      [['password'], 'safe', 'on' => 'update']
    ];
  }

  public function phoneValidate()
  {
    $util = PhoneNumberUtil::getInstance();
    try{
      $util->parse($this->phoneNumber);
    }catch (NumberParseException $e){
      $this->addError('phoneNumber','Numero de telefono invalido');
    }
  }

  public function attributeLabels()
  {
    return [
      'displayName' => 'Nombre',
      'email' => 'Correo',
      'phoneNumber' => 'Numero de telefono',
      'password' => 'Contraseña'
    ];
  }

  public function attributeHints()
  {
    return ['phoneNumber' => 'Debe ingresar el numero con codigo de pais eje. (colombia: +573203404172)'];
  }
}
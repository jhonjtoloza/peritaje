<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Rellenado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rellenado-form">

  <?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true
  ]); ?>
  <?= $form->field($model, 'label')
    ->textInput(['maxlength' => true, 'autofocus' => true]) ?>

  <?= $form->field($model, 'columna')->textInput(['maxlength' => true]) ?>

  <?= $form->field($model, 'espacio')
    ->textInput(['maxlength' => true, 'style' => 'text-transform: uppercase;']) ?>
  <?= $form->field($model, 'type')->dropDownList([
    'TEXT' => 'TEXTO',
    'IMAGE' => 'IMAGENES'
  ]) ?>
  <hr>
  <?= $form->field($model, 'opciones')
    ->checkbox() ?>

  <?= $form->field($model, 'val_opciones')
    ->widget(\unclead\multipleinput\MultipleInput::class, [
      'min' => 0,
      'columns' => [
        [
          'name' => 'opcion',
          'title' => 'Nombre de la opcion'
        ],
        [
          'name' => 'espacio',
          'title' => 'Espacio en Ecxel',
          'options' => [
            'style' => 'text-transform: uppercase;'
          ]
        ],
        [
          'name' => 'marca',
          'title' => 'Marcar con (eje "X")',
          'options' => [
            'style' => 'text-transform: uppercase;'
          ]
        ]
      ]
    ]) ?>

  <div class="form-group">
    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>

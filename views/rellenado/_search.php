<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RellenadoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rellenado-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'columna') ?>

    <?= $form->field($model, 'espacio') ?>

    <?= $form->field($model, 'opciones') ?>

    <?= $form->field($model, 'val_opciones') ?>

    <?php // echo $form->field($model, 'label') ?>

    <?php // echo $form->field($model, 'seccion_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

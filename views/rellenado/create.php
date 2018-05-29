<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Rellenado */

$this->title = 'Create Rellenado';
$this->params['breadcrumbs'][] = ['label' => 'Rellenados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rellenado-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

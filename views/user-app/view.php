<?php

/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 28/05/18
 * Time: 03:27 PM
 */

/* @var $this \yii\web\View */
/* @var $user \Kreait\Firebase\Auth\UserRecord */
?>
<div class="col-lg-6 col-lg-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h2 class="panel-title">Usuario de applicación <?= $user->displayName ?></h2>
    </div>
    <div class="panel-body">
      <?= \yii\helpers\Html::a('Editar', ['user-app/edit', 'id' => $user->uid], ['class' => 'btn btn-primary pull-right']) ?>
      <?= \yii\widgets\DetailView::widget([
        'model' => $user,
        'attributes' => [
          [
            'label' => 'Correo',
            'value'=>$user->email
          ],
          [
            'label' => 'Nombre',
            'value'=>$user->displayName
          ],
          [
            'label' => 'Telefono',
            'value'=>$user->phoneNumber
          ],
        ]
      ]) ?>
    </div>
  </div>
</div>


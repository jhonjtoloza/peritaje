<?php

namespace app\controllers;

use app\models\Rellenado;
use app\models\RellenadoSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

/**
 * RellenadoController implements the CRUD actions for Rellenado model.
 */
class RellenadoController extends Controller
{
  /**
   * @inheritdoc
   */
  public function behaviors()
  {
    return [
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

  /**
   * Lists all Rellenado models.
   * @return mixed
   */
  public function actionIndex()
  {
    $searchModel = new RellenadoSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
      'searchModel' => $searchModel,
      'dataProvider' => $dataProvider,
    ]);
  }

  /**
   * Displays a single Rellenado model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionView($id)
  {
    return $this->render('view', [
      'model' => $this->findModel($id),
    ]);
  }

  /**
   * Creates a new Rellenado model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    $model = new Rellenado();
    if ($model->load(Yii::$app->request->post())) {
      if (Yii::$app->request->isAjax) {
        Yii::$app->response->format = 'json';
        return ActiveForm::validate($model);
      }
      if ($model->save()) {
        $model->refresh();
        $model->val_opciones = Json::decode($model->val_opciones);
        Yii::$app->session->setFlash('Rellenado creado correctamente de columna = ' . $model->columna);
      }
    }

    return $this->render('create', [
      'model' => $model,
    ]);
  }

  /**
   * Updates an existing Rellenado model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionUpdate($id)
  {
    $model = $this->findModel($id);
    $model->val_opciones = Json::decode($model->val_opciones);
    if ($model->load(Yii::$app->request->post())) {
      if (Yii::$app->request->isAjax) {
        Yii::$app->response->format = 'json';
        return ActiveForm::validate($model);
      }
      if ($model->save())
        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('update', [
      'model' => $model,
    ]);
  }

  /**
   * Deletes an existing Rellenado model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   * @throws \Throwable
   * @throws \yii\db\StaleObjectException
   */
  public function actionDelete($id)
  {
    $this->findModel($id)->delete();
    return $this->redirect(['index']);
  }

  /**
   * Finds the Rellenado model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Rellenado the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Rellenado::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }

  public function actionAutomatico()
  {
    $precolum = "std_int_patio_";
    $filas = [
      'Piso' => 95,
      'Techo' => 96,
      'Lavadero' => 97,
      'Muros' => 98,
      'Puerta' => 99,
      'Ventana/Vidrios' => 100
    ];
    $opciones = [
      'Bueno' => 'E',
      'Regular' => 'F',
      'Malo' => 'G'
    ];
    foreach ($filas as $key => $fila) {
      $label = str_replace('/', ' ', $key);
      $val_opciones = [];
      foreach ($opciones as $key2 => $opcione) {
        $val_opciones[] = [
          'opcion' => strtoupper($key2),
          'espacio' => $opcione . $fila,
          'marca' => 'X'
        ];
      }
      $rellenado = new Rellenado([
        'label' => $key,
        'columna' => strtolower($precolum . str_replace(' ', '_', $label)),
        'espacio' => 'X',
        'opciones' => '1',
        'val_opciones' => $val_opciones
      ]);
      $rellenado->save();
      //material
      $rellobs = new Rellenado([
        'label' => 'Material',
        'opciones' => 0,
        'espacio' => "I" . $fila,
        'columna' => strtolower($precolum . str_replace(' ', '_', $label)) . "_material"
      ]);
      $rellobs->save();
      //observaciones
      $rellobs = new Rellenado([
        'label' => 'Observaciones',
        'opciones' => 0,
        'espacio' => "Q" . $fila,
        'columna' => strtolower($precolum . str_replace(' ', '_', $label)) . "_observaciones"
      ]);
      $rellobs->save();
    }
  }

  public function actionGenerate()
  {
    $selection = Yii::$app->request->post('selection');
    $models = Rellenado::findAll(['id' => $selection]);
    return $this->render('generador', [
      'models' => $models,
      'seccion' => Yii::$app->request->post('seccion')
    ]);
  }

  public function actionArreglar()
  {
    $selection = Yii::$app->request->post('selection');
    $models = Rellenado::findAll(['id' => $selection]);
    foreach ($models as $model) {
      $opciones = Json::decode($model->val_opciones);
      foreach ($opciones as $key => $opcion) {
        $opcion['espacio'] = str_replace('G', 'H', $opcion['espacio']);
        $opcion['espacio'] = str_replace('F', 'G', $opcion['espacio']);
        $opcion['espacio'] = str_replace('E', 'F', $opcion['espacio']);
        $opciones[$key] = $opcion;
      }
      $model->val_opciones = Json::encode($opciones);
      $model->save(false);
    }
  }
}

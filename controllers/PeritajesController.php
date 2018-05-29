<?php
/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 19/04/18
 * Time: 01:14 AM
 */

namespace app\controllers;


use app\models\FileUpload;
use app\models\Rellenado;
use google\appengine\api\cloud_storage\CloudStorageTools;
use Google\Cloud\Firestore\DocumentSnapshot;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\Query;
use Google\Cloud\Storage\StorageClient;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;

class PeritajesController extends Controller
{
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['logout'],
        'rules' => [
          [
            'actions' => ['index', 'generate-excel', 'ver', 'borrar', 'export-page'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
    ];
  }

  /** @var FirestoreClient */
  public $firestore;

  public function beforeAction($action)
  {
    $this->firestore = new FirestoreClient([
      'projectId' => 'peritaje-vivienda',
      'keyFile' => Json::decode(file_get_contents('sdk.json'))
    ]);
    return parent::beforeAction($action);
  }

  public function actionIndex()
  {
    $count = $this->firestore->collection('peritajes')
      ->documents()->size();
    $peritajes = $this->firestore->collection('peritajes')
      ->orderBy('date_create', Query::DIR_DESCENDING)
      ->documents();
    $page = Yii::$app->request->get('page', 1);
    $per_page = Yii::$app->request->get('per-page', 20);
    $pages = array_chunk($peritajes->rows(), $per_page);
    $pageModels = $pages[$page - 1];
    $peritajesArray = [];
    /** @var DocumentSnapshot $peritaje */
    foreach ($pageModels as $key => $peritaje) {
      $peritajesArray[] = $peritaje->data();
    }
    $arrayDataProvider = new ArrayDataProvider([
      'models' => $peritajesArray,
      'totalCount' => $count,
      'pagination' => [
        'totalCount' => $count,
        'pageSize' => 20,
      ]
    ]);
    return $this->render('index', [
      'data' => $arrayDataProvider,
    ]);

  }

  public function actionGenerateExcel($id)
  {
    ini_set('max_execution_time', 5 * 60); // 5 minutes
    $model = $this->firestore->document("peritajes/$id")
      ->snapshot()->data();
    $columns = array_keys($model);
    $rellenado = Rellenado::findAll(['columna' => $columns]);
    $excel = IOFactory::load('format.xlsx');
    $sheet = $excel->getActiveSheet();

    //set logo

    $logo = imagecreatefrompng($this->getLogoUrl());
    $Imgwriter = new MemoryDrawing();
    $Imgwriter->setImageResource($logo);
    $Imgwriter->setCoordinates("A1");
    $Imgwriter->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
    $Imgwriter->setMimeType(MemoryDrawing::MIMETYPE_JPEG);
    $Imgwriter->setHeight(200);
    $Imgwriter->setWidth(350);
    $Imgwriter->setName("Logo");
    $Imgwriter->setWorksheet($sheet);
    $Imgwriter->setOffsetX(0);
    $Imgwriter->setOffsety(0);

    foreach ($rellenado as $item) {
      if ($item->type == 'TEXT') {
        if (!$item->opciones) {
          $sheet->setCellValue($item->espacio, $model[$item->columna]);
        } else {
          $options = Json::decode($item->val_opciones);
          $keyspacio = array_search($model[$item->columna], array_column($options, 'opcion'));
          \Yii::info($item->columna . $keyspacio);
          if ($keyspacio !== false) {
            $spacio = $options[$keyspacio]['espacio'];
            $marca = $options[$keyspacio]['marca'];
            \Yii::info($spacio . "-" . $marca);
            $sheet->setCellValue($spacio, $marca);
            if ($marca == 'SI' or $marca == 'NO') {
              $sheet->getStyle($spacio)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('');
              $sheet->getStyle($spacio)->getFont()
                ->getColor()->setARGB(Color::COLOR_WHITE);
            } else {
              $sheet->getStyle($spacio)
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
          }
        }
      } else {
        if ($model[$item->columna] != '') {
          $img = imagecreatefromjpeg($model[$item->columna]);
          $Imgwriter = new MemoryDrawing();
          $Imgwriter->setImageResource($img);
          $Imgwriter->setCoordinates($item->espacio);
          $Imgwriter->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
          $Imgwriter->setMimeType(MemoryDrawing::MIMETYPE_JPEG);
          if ($item->columna == 'foto_fachada') {
            $Imgwriter->setHeight(320);
            $Imgwriter->setWidth(500);
          } else {
            $Imgwriter->setHeight(220);
            $Imgwriter->setWidth(380);
          }
          $Imgwriter->setName($item->label);
          $Imgwriter->setWorksheet($sheet);
          $Imgwriter->setOffsetX(0);
          $Imgwriter->setOffsety(0);
        }
      }
    }
    $writer = new Xlsx($excel);
    $path = Yii::$app->runtimePath;
    $writer->save($path . '/' . $id . '.xlsx');
    \Yii::$app->response->sendFile($path . '/' . $id . '.xlsx');
  }

  public function actionVer($id)
  {
    $model = $this->firestore->document("peritajes/$id")
      ->snapshot()->data();
    $columns = Rellenado::find()
      ->select(['columna', 'type'])
      ->orderBy('seccion_id')
      ->createCommand()
      ->queryAll();
    $viewColumns = [];
    foreach ($columns as $column) {
      $viewColumns[] = [
        'attribute' => $column['columna'],
        'format' => $column['type'] == 'IMAGE' ? 'image' : 'text',
        ''
      ];
    }
    return $this->render('vista', [
      'coloumns' => $viewColumns,
      'model' => $model
    ]);
  }

  public function actionBorrar($id)
  {
    $resutl = $this->firestore->document("/peritajes/$id")
      ->delete();
    Yii::info($resutl);
    return $this->redirect(['peritajes/index']);
  }

  public function actionExportPage()
  {
    ini_set('memory_limit', '2048M');
    $excel = IOFactory::load('matrix-format.xlsx');
    $sheet = $excel->getActiveSheet();
    $peritajes = $this->firestore->collection('peritajes')
      ->orderBy('date_create', Query::DIR_DESCENDING)
      ->documents();
    $page = Yii::$app->request->get('page', false);
    $per_page = Yii::$app->request->get('per-page', 1);

    if ($page !== false) {
      $pages = array_chunk($peritajes->rows(), $per_page);
      $pageModels = $pages[$page - 1];
    } else
      $pageModels = $peritajes->rows();

    $logo = imagecreatefrompng($this->getLogoUrl());
    $Imgwriter = new MemoryDrawing();
    $Imgwriter->setImageResource($logo);
    $Imgwriter->setCoordinates("A1");
    $Imgwriter->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
    $Imgwriter->setMimeType(MemoryDrawing::MIMETYPE_JPEG);
    $Imgwriter->setHeight(200);
    $Imgwriter->setWidth(350);
    $Imgwriter->setName("Logo");
    $Imgwriter->setWorksheet($sheet);
    $Imgwriter->setOffsetX(0);
    $Imgwriter->setOffsety(0);

    $sheet->insertNewRowBefore(8, count($pageModels));
    $columns = [
      'persona_atiende' => 'K',
      'barrio' => 'G',
      'direccion' => 'B',
      'telefono' => 'R',
      'obsevaciones_generales' => 'AE',
      'tendencia tendencia_cual' => [
        'PROPIETARIO' => 'U',
        'ARRENDATARIO' => 'V',
        'INQUILINO' => 'W',
        'FAMILIAR' => 'X',
        'REPRESENTANTE' => 'Y'
      ],
      'tipo_predio tipo_predio_cual' => [
        'VIVIENDA' => 'Z',
        'COMERCIAL' => 'AB',
        'INDUSTRIAL' => 'AD',
        'INSTITUCIONAL' => 'AC',
        'SIN IDENTIFICAR' => '',
        'RECREACIONAL' => '',
        'I. EDUCATIVA' => 'AC',
        'APARTAMENTO' => 'A',
        'BODEGA' => 'AD'
      ],
    ];
    $merge = [
      'B' => 'F',
      'G' => 'J',
      'K' => 'Q',
      'R' => 'T',
      'AE' => 'AH'
    ];
    $row = 7;
    foreach ($pageModels as $serial => $model) {
      $sheet->setCellValue("A$row", ($serial + 1))
        ->getStyle("A$row")
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER)
        ->setTextRotation(0);
      $model = $model->data();
      foreach ($columns as $key => $column) {
        $value = [];
        if (strpos($key, " ") !== false) {
          $keys = explode(' ', $key);
          foreach ($keys as $k)
            $value[] = isset($model[$k]) ? $model[$k] : '';
        } else {
          $value[] = isset($model[$key]) ? $model[$key] : '';
        }
        $column = $columns[$key];
        if (is_array($column)) {
          $keyvalues = array_keys($column, $key);
          $space = "";
          foreach ($value as $val) {
            $keyFind = array_search(strtoupper($val), $keyvalues);
            if ($keyFind !== false) {
              $space = $column[$keyFind];
            }
          }
          if ($space != '') {
            $sheet->setCellValue("$space$row", 'X');
            $sheet->getStyle("$space$row")
              ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
          }
        } else {
          $sheet->setCellValue("$column$row", $value[0]);
          $toMerge = isset($merge[$column]) ? $merge[$column] : null;
          $vMerge = "$column$row:$toMerge$row";
          Yii::info($vMerge);
          if ($toMerge != null)
            $sheet->mergeCells($vMerge);
        }

      }
      $row++;
    }
    $writer = new Xlsx($excel);
    $path = Yii::$app->runtimePath;
    $writer->save($path . '/matrix.xlsx');
    \Yii::$app->response->sendFile($path . '/matrix.xlsx');
  }

  public function actionLogo()
  {
    $model = new FileUpload();
    if ($model->load(Yii::$app->request->post())) {
      $model->picture = UploadedFile::getInstance($model, 'picture');
      Yii::info($model->picture);
      if ($model->picture) {
        $projectId = 'pperitaje-204822';
        $storage = new StorageClient([
          'projectId' => $projectId,
          'keyFilePath' => 'peritaje-sdk.json'
        ]);
        $storage->registerStreamWrapper();
        $storage->bucket('peritaje-204822.appspot.com');
        $pathTemp = Yii::$app->runtimePath;
        $from = "$pathTemp/logo.jpg";
        $model->picture->saveAs($from);
        $to = $gsTo = 'gs://peritaje-204822.appspot.com/logo.jpg';
        $options = ['gs' => ['acl' => 'public-read']];
        $context = stream_context_create($options);
        file_put_contents($to, file_get_contents($from), 0, $context);
        $publicUrl = CloudStorageTools::getPublicUrl($gsTo, false);
        Yii::info($publicUrl);
        Yii::$app->session->setFlash('success', 'Logo guardado correctamente');
      }
    }
    return $this->render('logo', [
      'model' => $model
    ]);
  }

  public function getLogoUrl()
  {
    $projectId = 'pperitaje-204822';
    $storage = new StorageClient([
      'projectId' => $projectId,
      'keyFilePath' => 'peritaje-sdk.json'
    ]);
    $storage->registerStreamWrapper();
    $bucket = $storage->bucket('peritaje-204822.appspot.com');
    $logoUrl = $bucket->object('logo.jpg');
    return $logoUrl->signedUrl(time() + 24 * 60 * 60);
  }
}
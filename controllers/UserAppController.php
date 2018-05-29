<?php
/**
 * Created by Jhon J Toloza.
 * User: jhon
 * Date: 9/05/18
 * Time: 09:39 AM
 */

namespace app\controllers;


use app\models\FireUser;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\Controller;

class UserAppController extends Controller
{
  /** @var FirestoreClient */
  public $firestore;
  /** @var Auth */
  public $fireAuth;

  public function beforeAction($action)
  {
    $this->firestore = new FirestoreClient([
      'projectId' => 'peritaje-vivienda',
      'keyFile' => Json::decode(file_get_contents('sdk.json'))
    ]);
    $file = ServiceAccount::fromJsonFile('sdk.json');
    $this->fireAuth = (new Factory())->withServiceAccount($file)->create()->getAuth();
    return parent::beforeAction($action);
  }

  public function actionIndex()
  {
    $users = [];
    $userGen = $this->fireAuth->listUsers();
    foreach ($userGen as $userRecord) {
      $users[] = $userRecord;
    };
    return $this->render('index', [
      'users' => new ArrayDataProvider(['allModels' => $users])
    ]);
  }

  public function actionCreate()
  {
    $model = new FireUser([
      'scenario' => 'create'
    ]);
    if ($model->load(\Yii::$app->request->post()) and $model->validate()) {
      \Yii::info($model->attributes);
      $userProperties = [
        'email' => $model->email,
        'emailVerified' => true,
        'phoneNumber' => $model->phoneNumber,
        'password' => $model->password,
        'displayName' => $model->displayName,
        'photoUrl' => \yii\helpers\Url::to("@web/uploads/nophoto.jpg", true),
        'disabled' => false,
      ];
      try {
        $createdUser = $this->fireAuth->createUser($userProperties);
        return $this->redirect(['user-app/view', 'id' => $createdUser->uid]);
      } catch (AuthException $e) {
        Yii::$app->session->setFlash('danger', $e->getMessage());
        Yii::info($e->getMessage());
      }
    }
    return $this->render('create', [
      'model' => $model
    ]);
  }

  public function actionEdit($id)
  {
    $user = $this->fireAuth->getUser($id);
    $model = new FireUser([
      'uid' => $user->uid,
      'scenario' => 'update',
      'displayName' => $user->displayName,
      'phoneNumber' => $user->phoneNumber,
      'email' => $user->email
    ]);
    if ($model->load(Yii::$app->request->post()) and $model->validate()) {
      $this->fireAuth->updateUser($model->uid, [
        'displayName' => $model->displayName,
        'phoneNumber' => $model->phoneNumber,
        'email' => $model->email
      ]);
      if ($model->password != '') {
        $this->fireAuth->changeUserPassword($model->uid, $model->password);
      }
      Yii::$app->session->setFlash('success','Editado correctamente');
      return $this->redirect(['user-app/index']);
    }
    return $this->render('create', [
      'model' => $model
    ]);
  }

  public function actionDelete($id){
    $this->fireAuth->deleteUser($id);
    Yii::$app->session->setFlash('success','Borrado correctamente');
    return $this->redirect(['user-app/index']);
  }

  public function actionView($id)
  {
    $user = $this->fireAuth->getUser($id);
    return $this->render('view',[
      'user'=>$user
    ]);
  }
}
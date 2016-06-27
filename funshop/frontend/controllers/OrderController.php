<?php

namespace frontend\controllers;

use common\models\OrderLog;
use common\models\OrderProduct;
use common\models\Product;
use Yii;
use common\models\Order;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class OrderController extends \frontend\components\Controller
{
    public $layout = 'column2';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->request->get('status')) {
            $status = Yii::$app->request->get('status');
            if (strpos($status, ',')) {
                $status = explode( ',', $status);
            }
            $query = Order::find()->where(['user_id' => Yii::$app->user->id, 'status' => $status]);
        } elseif (Yii::$app->request->get('sn')) {
            $query = Order::find()->where(['sn' => Yii::$app->request->get('sn')]);
        } else {
            $query = Order::find()->where(['and', 'user_id=' . Yii::$app->user->id, 'status > ' . Order::STATUS_DELETED]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['defaultPageSize' => Yii::$app->params['defaultPageSizeOrder']],
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        return $this->render('index', [
            'orders' => $dataProvider->getModels(),
            'pagination' => $dataProvider->pagination,
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'cart';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionAjaxStatus($id, $status)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model= $this->findModel($id);

        if ($model) {
            $oldStatus = $model->status;
            $model->status = $status;
            $model->save();

            // 记录订单日志
            $orderLog = new OrderLog([
                'order_id' => $model->id,
                'status' => $model->status,
            ]);
            $orderLog->save();

            // 如果订单为取消，则恢复对应的库存
            if ($oldStatus > 0 && $status == Order::STATUS_CANCEL) {
                $orderProducts = OrderProduct::find()->where(['order_id' => $model->id])->all();
                foreach ($orderProducts as $product) {
                    Product::updateAllCounters(['stock' => $product->number], ['id' => $product->product_id]);
                }
            }

            return [
                'status' => 1,
            ];
        }
        return [
            'status' => -1,
        ];
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

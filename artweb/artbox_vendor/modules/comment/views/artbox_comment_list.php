<?php
    use artweb\artbox\modules\comment\models\CommentModel;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ListView;
    use yii\widgets\Pjax;
    
    /**
     * @var CommentModel       $comment_model
     * @var array              $list_params
     * @var array              $item_options
     * @var string             $item_view
     * @var Model              $model
     * @var ActiveDataProvider $comments
     * @var View               $this
     */
    Pjax::begin();
    if(( $success = \Yii::$app->session->getFlash('artbox_comment_success') ) != NULL) {
        echo Html::tag('p', $success);
    }
    echo ListView::widget([
        'dataProvider' => $comments,
        'itemOptions'  => $item_options,
        'itemView'     => $item_view,
        'summary'      => '',
    ]);
    Pjax::end();
    
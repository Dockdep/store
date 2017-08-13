<?php
    use frontend\models\PaymentInform;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View          $this
     * @var PaymentInform $model
     */
    echo Html::tag('p', $model->getAttributeLabel('orderId') . ':' . $model->orderId);
    echo Html::tag('p', $model->getAttributeLabel('name') . ':' . $model->name);
    echo Html::tag('p', $model->getAttributeLabel('address') . ':' . $model->address);
    echo Html::tag('p', $model->getAttributeLabel('sum') . ':' . $model->sum);
    echo Html::tag('p', $model->getAttributeLabel('bank') . ':' . $model->bank);
    echo Html::tag('p', $model->getAttributeLabel('payedOn') . ':' . $model->payedOn);
    echo Html::tag('p', $model->getAttributeLabel('payedAt') . ':' . $model->payedAt);
    echo Html::tag('p', $model->getAttributeLabel('checkNum') . ':' . $model->checkNum);
    echo Html::tag('p', $model->getAttributeLabel('comment') . ':' . $model->comment);
?>


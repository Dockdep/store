<?php
    use artweb\artbox\language\models\Language;
    use yii\db\ActiveRecord;
    use yii\helpers\Html;
    use yii\web\View;
    use yii\widgets\ActiveForm;
    
    /**
     * @var Language[]   $languages
     * @var string       $form_view
     * @var ActiveRecord $modelLangs
     * @var ActiveForm   $form
     * @var View         $this
     * @var string       $idPrefix
     */
?>
<div>
    <?php
        if(count($languages) > 1) {
            ?>
            <ul class="nav nav-tabs text-uppercase">
                <?php
                    $first = true;
                    foreach($modelLangs as $lang => $model_lang) {
                        if(!array_key_exists($lang, $languages)) {
                            continue;
                        }
                        echo Html::tag('li', Html::a($languages[ $lang ]->url, [
                            '',
                            '#' => $idPrefix . '_' . $lang,
                        ], [ 'data-toggle' => 'tab' ]), [
                            'class' => $first ? 'active' : '',
                        ]);
                        $first = false;
                    }
                ?>
            </ul>
            <div class="tab-content">
                <?php
                    $first = true;
                    foreach($modelLangs as $lang => $model_lang) {
                        if(!array_key_exists($lang, $languages)) {
                            continue;
                        }
                        echo Html::tag('div', $this->render($form_view, [
                            'model_lang' => $model_lang,
                            'language'   => $languages[ $lang ],
                            'form'       => $form,
                        ]), [
                            'class' => 'tab-pane' . ( $first ? ' active' : '' ),
                            'id'    => $idPrefix . '_' . $lang,
                        ]);
                        $first = false;
                    }
                ?>
            </div>
            <?php
        } else {
            $language = current($languages);
            if(isset( $modelLangs[ $language->id ] )) {
                echo $this->render($form_view, [
                    'model_lang' => $modelLangs[ $language->id ],
                    'language'   => $language,
                    'form'       => $form,
                ]);
            }
        }
    ?>
</div>

<?php
    use artweb\artbox\language\components\LanguageRequest;
    use artweb\artbox\language\models\Language;
    use yii\bootstrap\Html;
    
    /**
     * @var Language   $current   Current language
     * @var Language[] $languages Available languages
     */
?>
<div id="language_picker">
    <span id="current_language">
        <?php
            echo $current->name;
        ?>
        <span class="show-more-language">▼</span>
    </span>
    <ul id="languages">
        <?php
            foreach($languages as $language) {
                ?>
                <li class="item-language">
                    <?php
                        /**
                         * @var LanguageRequest $request
                         */
                        $request = \Yii::$app->getRequest();
                        echo Html::a($language->name, '/' . $language->url . $request->getLanguageUrl());
                    ?>
                </li>
                <?php
            }
        ?>
    </ul>
</div>

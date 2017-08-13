<?php
use artweb\artbox\ecommerce\helpers\FilterHelper;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
/**
 *@var  ArrayDataProvider $productProvider
 */

/**
 * @param $count
 * @param $totalCount
 * @param $status
 * @return string
 */
function formatCount($count, $totalCount, $status){
    if($status){
        $count = $count - $totalCount;
        return  $count > 0 ? '+'.$count : $count;
    } else {
        return $count;
    }
}

?>
<div class="col-xs-12 visible-xs visible-sm">
    <div class="style filter_mobile_">
        <a href="#">
            <?php


            echo \Yii::t('app', 'фильтр');
            ?>
        </a>
    </div>
</div>
<div class="sidebar-transform visible-md visible-lg col-md-3 col-lg-3">
    <div class="hidden-md hidden-lg close_filters"></div>
    <?php if(!empty($filter)){ ?>
    <div class="style clear_filters-wr">
        <div class="style title-clear_f">выбранные фильтры</div>
        <div class="style wrapp_clear-link">
            <?php
                if(!empty($filter['special']) && in_array('top', $filter['special'])){ ?>
                    <a href="<?= Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, 'special', 'top', true)])  ?>">Top</a>
                <?php }
                if(!empty($filter['special']) && in_array('promo', $filter['special'])){ ?>
                    <a href="<?= Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, 'special', 'promo', true)])  ?>">Акция</a>
                <?php }
                if(!empty($filter['special']) && in_array('new', $filter['special'])){ ?>
                    <a href="<?= Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, 'special', 'new', true)])  ?>">New</a>
                <?php }
            foreach($groups as $group_name => $group) {
                foreach($group as $option) {
                    if( isset($filter[$option['group_alias']]) && in_array($option['option_alias'], $filter[$option['group_alias']])){?>
                        <a href="<?= Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, $option['group_alias'], $option['option_alias'], true)]) ?>"><?= $option['value']?></a>
                    <?php }
                }
            }?>
        </div>
        <div class="style reset-btn-filters">
            <?php
            $checked = !empty($filter['special']) && in_array('top', $filter['special']);
            $option_url = Url::to(['catalog/category', 'category' => $category, 'filters' =>[]]);
            ?>
            <a href="<?= $option_url?>">сбросить все фильтры</a>
        </div>
    </div>
    <?php }?>
    <div id="filters" data-count="<?= $productProvider->getTotalCount()?>" data-category="<?= $category->id?>"  class="sidebar style">
        <form action="">

            <div class="buttonsSort style">
                <ul>
                    <?php
                    $checked = !empty($filter['special']) && in_array('promo', $filter['special']);
                    $option_url = Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, 'special', 'promo', $checked)]);
                    ?>
                    <li class="<?= $checked ?'active' : ''?> sale_btn_f col-sm-4 col-md-4 col-lg-4">

                        <a href="<?= $option_url?>">акция</a>

                    </li>
                    <?php
                    $checked = !empty($filter['special']) && in_array('new', $filter['special']);
                    $option_url = Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, 'special', 'new', $checked)]);
                    ?>
                    <li class="<?= $checked ?'active' : ''?>  col-sm-4 new_btn_f col-md-4 col-lg-4">

                        <a href="<?= $option_url?>">new</a>

                    </li>
                    <?php
                    $checked = !empty($filter['special']) && in_array('top', $filter['special']);
                    $option_url = Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, 'special', 'top', $checked)]);
                    ?>
                    <li class="<?= $checked ?'active' : ''?>  col-sm-4 top_sale_btn_f col-md-4 col-lg-4">

                        <a href="<?= $option_url?>">топ</a>
                    </li>
                </ul>
            </div>

            <?php foreach($groups as $group_name => $group) {?>
                <?php
                $active = false;
                    foreach($group as $option) {



                            if( isset($filter[$option['group_alias']]) && in_array($option['option_alias'], $filter[$option['group_alias']])){
                                $active = true;
                            }



                    }
                ?>
                <div class="col-sm-12 col-md-12 col-lg-12 input-blocks-wrapper">
                    <div class="input-blocks<?php
                      if (preg_match('@размер@iu', $group_name) || preg_match('@розм@iu', $group_name)) {
                        echo ' sizes';
                      }
                    ?>">

                        <label for="_"><?= $group_name?></label>


                        <?php foreach($group as $option){

                            $checked = (isset($filter[$option['group_alias']]) && in_array($option['option_alias'], $filter[$option['group_alias']]));

                            $option_url = Url::to(['catalog/category', 'category' => $category, 'filters' => FilterHelper::getFilterForOption($filter, $option['group_alias'], $option['option_alias'], $checked)]);
                            ?>
                            <div class="sidebar_checks" data-checked="<?= $checked ? ' true' : 'false'?>">
                                <input <?= isset($option['count']) && $option['count'] == 0 ? 'disabled' : ''  ?>  id="<?= $option['tax_option_id']?>"  class="custom-check" type="checkbox" <?= $checked ? ' checked' : ''?>  value="1">
                                <label for="<?= $option['tax_option_id']?>"><span class="features-option"></span>
                                    <a  class="filter-link <?= isset($option['count']) && $option['count'] == 0 ? 'disabled-link' : ''  ?>"  href="<?= $option_url?>"><?= $option['value']?>
                                        <p class="link-count" style="display: inline-block"> <?= isset($option['count']) ? '('.formatCount($option['count'],$productProvider->getTotalCount(),$active).')' : '' ?></p>
                                    </a>
                                </label>
                            </div>
                        <?php } ?>


                    <div class="filter-status" style="display: none"></div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($priceLimits->min < $priceLimits->max) :

                $filterWhitoutPrice = $filter;
                $filterWhitoutPrice['prices'] = [
                    'min' => '{from}',
                    'max' => '{to}',
                ];

                ?>
            <div class="col-sm-12 col-md-12 col-lg-12 input-blocks-wrapper">
                <div id="price_block" class="input-blocks"
                     data-min="<?=$priceLimits->min?>"
                     data-max="<?=$priceLimits->max?>"
                     data-from="<?=((isset($filter['prices']) && !empty($filter['prices']['min'])) ? $filter['prices']['min'] : $priceLimits->min)?>"
                     data-to="<?=(( isset($filter['prices']) && !empty($filter['prices']['max'])) ? $filter['prices']['max'] :  $priceLimits->max)?>"
                     data-url="<?= Url::to(['catalog/category','category' => $category,'filters' => $filterWhitoutPrice])?>"
                >
                    <label>Цена</label>
                    <div class="price_filter first_price_li">
                        <div class="price_slider">
                            <input type="text" id="price_interval" name="price_interval" value="" />
                        </div>
                    </div>

                    <div class="style price_inputs">
                        <div class="row">
                            <div class="col-xs-4 col-sm-2 col-md-4" style="padding-right: 0;">
                                <input id="filter-prices-min" type="text" min="<?=$priceLimits->min?>" max="<?=$priceLimits->max?>" value="<?=((isset($filter['prices']) && !empty($filter['prices']['min'])) ? $filter['prices']['min'] : $priceLimits->min)?>">
                            </div>
                            <div class="col-xs-1 col-sm-1 separator-price"></div>
                            <div class="col-xs-4 col-sm-2 col-md-4" style="padding-left: 0;">
                                <input id="filter-prices-max" type="text" min="<?=$priceLimits->min?>" max="<?=$priceLimits->max?>" value="<?=(( isset($filter['prices']) && !empty($filter['prices']['max'])) ? $filter['prices']['max'] :  $priceLimits->max)?>">
                            </div>
                            <div class="col-xs-2" id="btn_ok"><span>ok</span></div>
                        </div>
                    </div>

                </div>
            </div>
            <?php endif?>


            <!------------------------------------------->
<!--            <div class="col-sm-12 col-md-12 col-lg-12 input-blocks-wrapper">-->
<!--                <div id="price_block" class="input-blocks"-->
<!--                     data-min="640"-->
<!--                     data-max="6721"-->
<!--                     data-from="640"-->
<!--                     data-to="6721"-->
<!--                     data-url="/ru/catalog/lyzhi-begovye/filters:prices={from}:{to}"-->
<!--                >-->
<!--                    <label>Цена</label>-->
<!--                    <div class="price_filter first_price_li">-->
<!--                        <div class="price_slider">-->
<!--                            <input type="text" id="price_interval" name="price_interval" value="" />-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!---->
<!--                    <div class="style price_inputs">-->
<!--                        <div class="row">-->
<!--                            <div class="col-xs-4 col-sm-2 col-md-4" style="padding-right: 0;">-->
<!--                                <input id="filter-prices-min" type="text" min="640" max="6721" value="640">-->
<!--                            </div>-->
<!--                            <div class="col-xs-1 col-sm-1 separator-price"></div>-->
<!--                            <div class="col-xs-4 col-sm-2 col-md-4" style="padding-left: 0;">-->
<!--                                <input id="filter-prices-max" type="text" min="640" max="6721" value="6721">-->
<!--                            </div>-->
<!--                            <div class="col-xs-2" id="btn_ok"><span>ok</span></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!---->
<!--            </div>-->
            <!------------------------------------------->



        </form>

    </div>
</div>
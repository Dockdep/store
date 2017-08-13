<?php

use artweb\artbox\seo\widgets\Seo;
use frontend\models\SignupForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View   $this
 * @var SignupForm $model
 */


$this->title = \Yii::t('app','my_cabinet');
$this->params['breadcrumbs'][] = [
	'label' => Html::tag(
		'span',
		$this->title,
		[
			'itemprop' => 'name',
		]
	),
	'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
];
$this->params[ 'seo' ][ Seo::H1] =  $this->title ;
$this->params[ 'seo' ][ Seo::TITLE] = $this->title;
?>
		<div style="padding-bottom: 9px;" class="col-xs-12 col-sm-12 col-md-8 col-lg-9">
			<div class="artbox_cab-forms-wr style">
				<div class="artbox_cab-forms-bg"></div>
				<div class="artbox_cab-forms">
					<ul class="mob-cab-list">
						<li class="active-2">
							<?= Html::a("<div class='menu-ico_1'></div>".\Yii::t('app','данные'), Url::to(['cabinet/main']),[
								"class" => "mob-cab-link"
							]);?>
							<div class="artbox_cab-title"><?= \Yii::t('app','personal_data')?></div>
							<div class="artbox_start-form style">
								<?php $form = ActiveForm::begin([]); ?>
<!--									<div class="input-blocks-wrapper add_foto-avatar">-->
<!--										<div class="form_title">Фото</div>-->
<!--										<div id="image_widget_block">-->
<!--											<div class="row">-->
<!--												<input type="hidden" id="image_picture_link" name="Banner[image]" value="/storage/user_1/5fdb5cb2cb9b35ec8b6524f141015930119/100x100.jpg">-->
<!--												<input type="hidden" id="image_old_img" name="ImageSizerForm[old_img]" value="/storage/user_1/ee192daa1647a243d0d95c923dddc496750/100x100.jpg">-->
<!--												<input type="hidden" id="image_new_img" name="ImageSizerForm[new_img]" value="/storage/user_1/5fdb5cb2cb9b35ec8b6524f141015930119/100x100.jpg">-->
<!--												<input type="hidden" id="image_row_id" name="ImageSizerForm[new_img]" value="1">-->
<!--												<input type="hidden" id="image_row_field" name="ImageSizerForm[field]" value="image">-->
<!--												<div id="image_img_block">-->
<!--													<div class="col-xs-6 col-md-3 image-container">-->
<!--														<div id="image_buttons_block">-->
<!--															<span title="удалить" id="image_remove_img" class="glyphicon glyphicon-repeat"></span>-->
<!--															<span id="image_save_img" class="glyphicon glyphicon-ok">сохранить</span>-->
<!--														</div>-->
<!--														<a class="thumbnail" href="#">-->
<!--															<img src="/storage/user_1/5fdb5cb2cb9b35ec8b6524f141015930119/100x100.jpg" alt="">-->
<!--														</a>-->
<!--													</div>-->
<!--												</div>-->
<!--											</div>-->
<!--											<div class="row">-->
<!--                                        <span class="btn btn-success fileinput-button uploader-button">-->
<!--                                            <span>добавить фото</span>-->
<!--                                        </span>-->
<!--											</div>-->
<!--										</div>-->
<!---->
<!---->
<!--										<div class="form_help_bl">-->
<!--											<div class="_title_">Фото</div>-->
<!--											<div class="_help_">Вы можете  загрузить в качестве аватара любое изображение в формате jpeg, gif, png размером не более 1Мб</div>-->
<!--										</div>-->
<!--									</div>-->

									<div class="input-blocks-wrapper">
										<div class="left_form">
											<div class="input-blocks">
												<?= $form->field($model, 'username')->textInput() ?>
											</div>
										</div>

										<div class="form_help_bl">
											<div class="_title_"><?= \Yii::t('app','your_name')?></div>
											<div class="_help_"><?= \Yii::t('app','your_name_2')?></div>
										</div>
									</div>

									<div class="input-blocks-wrapper">
										<div class="left_form">
											<div class="input-blocks">
												<?= $form->field($model, 'email')->textInput() ?>
											</div>
											<div class="input-blocks">
												<?= $form->field($model, 'phone')->textInput([
													"placeholder" => "+38(_ _ _) _ _ _- _ _ - _ _"
												]) ?>
											</div>
										</div>

										<div class="form_help_bl">
											<div class="_title_"><?= \Yii::t('app','contacts')?></div>
											<div class="_help_"><?= \Yii::t('app','contacts2')?></div>
										</div>
									</div>

									<div class="input-blocks-wrapper _about_">
										<div class="left_form">
											<div class="input-blocks">
												<?= $form->field($model, 'birthday')->textInput([
													'class' => '_datepicer'
												]) ?>
											</div>
											<div class="input-blocks min-select">
												<?= $form->field($model, 'gender')->dropDownList([
													'0' => 'Выберите пол...',
													'Мужской' => 'Мужской',
													'Женский' => 'Женский',
												],['options' => [0 => ['disabled' => true, 'selected' => true]
												]]); ?>
											</div>
										</div>

										<div class="form_help_bl">
											<div class="_title_"><?= \Yii::t('app','about')?></div>
											<div class="_help_"><?= \Yii::t('app','about2')?></div>
										</div>
									</div>

								<div class="input-blocks-wrapper _about_">
									<div class="left_form">
										<div class="input-blocks">
											<?= $form->field($model, 'password')->passwordInput() ?>
										</div>
										<div class="input-blocks">
											<?= $form->field($model, 'password_repeat')->passwordInput() ?>
										</div>
									</div>

									<div class="form_help_bl">
										<div class="_title_"><?= \Yii::t('app','secure')?></div>
										<div class="_help_"><?= \Yii::t('app','secure2')?></div>
									</div>
								</div>


									<div class="admin-save-btn style">
										<button class="btn" type="submit"><?= \Yii::t('app','save')?></button>
									</div>
								<?php
								ActiveForm::end();
								?>

								<?php
								$this->registerJsFile(Yii::getAlias('//code.jquery.com/ui/1.11.4/jquery-ui.js'),[
									'position' => View::POS_END,
									'depends' => ['yii\web\JqueryAsset']
								]); ?>
								<?php

								$js = "

								$('#customer-phone').mask('+38(000)000-00-00');
								$( '._datepicer' ).datepicker({
									changeMonth: true,
									changeYear: true,
									dateFormat: 'dd.mm.yy',
									closeText: 'Закрыть',
									prevText: 'Пред',
									nextText: 'След',
									monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
									monthNamesShort: ['Январь','Февраль','Март','Апрель','Май','Июнь', 'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
									dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
									dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
									dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
									firstDay: 1,
									defaultDate:'01.01.1990'
								});";
								$this->registerJs($js,View::POS_READY);

								?>

							</div>
						</li>
						<li>
							<?= Html::a("<div class='menu-ico_5'></div>".\Yii::t('app','заказы'), Url::to(['cabinet/my-orders']),[
								"class" => "mob-cab-link"
							]);?>
						</li>
						<li>
							<?= Html::a("<div class='menu-ico_6'></div>".\Yii::t('app','просмотры'), Url::to(['cabinet/history']),[
								"class" => "mob-cab-link"
							]);?>
						</li>
						<li>
							<?= Html::a("<div class='menu-ico_9'></div>".\Yii::t('app', "выход"),Url::to(['site/logout']),[
								"class" => "mob-cab-link"
							]);?>
						</li>
					</ul>
				</div>
			</div>
		</div>

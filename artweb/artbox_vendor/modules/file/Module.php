<?php
namespace artweb\artbox\modules\file;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module
{
	public function init()
	{
		parent::init();
		
		\Yii::configure($this, require(__DIR__.'/config.php'));
	}

}

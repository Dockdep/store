<?php
    
    namespace artweb\artbox\modules\language\widgets;
    
    use artweb\artbox\modules\language\models\Language;
    use yii\base\InvalidConfigException;
    use yii\bootstrap\Widget;
    use yii\db\ActiveRecord;
    use yii\widgets\ActiveForm;
    
    class LanguageForm extends Widget
    {
        
        /**
         * @var ActiveRecord[] $modelLangs
         */
        public $modelLangs = [];
        
        /**
         * @var string $formView
         */
        public $formView;
        
        /**
         * @var string $idPrefix
         */
        public $idPrefix = 'lang';
        
        /**
         * @var ActiveForm $form
         */
        private $form;
        
        /**
         * @var Language[] $languages
         */
        private $languages = [];
        
        public function init()
        {
            parent::init();
            if($this->formView === NULL) {
                throw new InvalidConfigException('Form view must be set');
            }
            if(empty( $this->modelLangs ) || !is_array($this->modelLangs)) {
                throw new InvalidConfigException('Language models must be passed');
            }
            if(empty( $this->getForm() )) {
                throw new InvalidConfigException('Form model must be set');
            }
            $this->languages = Language::find()
                                       ->where([ 'status' => true ])
                                       ->orderBy([ 'default' => SORT_DESC ])
                                       ->indexBy('id')
                                       ->all();
        }
        
        public function run()
        {
            return $this->render('language_form_frame', [
                'languages'   => $this->languages,
                'form_view'   => $this->formView,
                'modelLangs' => $this->modelLangs,
                'form'        => $this->getForm(),
                'idPrefix'   => $this->idPrefix,
            ]);
        }
        
        public function getForm(): ActiveForm
        {
            return $this->form;
        }
        
        public function setForm(ActiveForm $value)
        {
            $this->form = $value;
        }
    }
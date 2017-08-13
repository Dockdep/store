<?php

namespace artweb\artbox\components\artboxtree;

use Yii;
use yii\base\Widget;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;

class ArtboxTreeWidget extends Widget
{

    /**
     * @var \yii\data\DataProviderInterface the data provider for the view. This property is required.
     */
    public $dataProvider;

    /**
     * @var string
     */
    public $keyNameId = 'id';

    /**
     * @var string
     */
    public $keyNameParentId = 'parent_id';

    /**
     * @var integer or null
     */
    public $maxLevel = null;

    /**
     * @var integer
     */
    public $rootParentId = 0;

    /**
     * @var string
     */
    public $emptyResult;

    /**
     * @var boolean include the CSS and JS files. Default is true.
     * If this is set false, you are responsible to explicitly include the necessary CSS and JS files in your page.
     */
    public $assetBundle;

    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;

    /**
     * Init the widget object.
     */
    public function init()
    {
        parent::init();
        if ($this->dataProvider === null) {
            throw new InvalidConfigException('The "dataProvider" property must be set.');
        }
        if ($this->keyNameId === null) {
            throw new InvalidConfigException('The "keyNameId" property must be set.');
        }
        if ($this->formatter == null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        if (!empty($this->assetBundle) && class_exists($this->assetBundle)) {
            $view = $this->getView();
            $assetBundle = $this->assetBundle;
            $assetBundle::register($view);
        }
        if ($this->dataProvider->getCount() == 0) {
            return $this->renderEmptyResult();
        }

        parent::run();
    }

    protected function renderEmptyResult() {
        return empty($this->emptyResult) ? Yii::t('artbox', 'TreeViewEmptyResult') : Yii::t('artbox', $this->emptyResult);
    }

    /**
     * Normalize tree data
     * @param array $data
     * @param string $parentId
     * @return array
     */
    protected function normalizeTreeData(array $data, $parentId = null) {
        $result = [];
        foreach ($data as $element) {
            if ($element[$this->keyNameParentId] == $parentId) {
                $result[] = $element;
                $children = $this->normalizeTreeData($data, $element[$this->keyNameId]);
                if ($children) {
                    $result = array_merge($result, $children);
                }
            }
        }
        return $result;
    }

    /**
     * Hierarchy tree data
     * @param array $data
     * @param string $parentId
     * @return array
     */
    protected function hierarchyTreeData(array $data, $parentId = null) {
        $result = [];
        foreach ($data as $element) {
            if ($element[$this->keyNameParentId] == $parentId) {
                $children = $this->hierarchyTreeData($data, $element[$this->keyNameId]);
                $result[] = [
                    'item' => $element,
                    'children' => $children
                ];
            }
        }
        return $result;
    }
}
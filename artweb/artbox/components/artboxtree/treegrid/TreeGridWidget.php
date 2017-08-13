<?php

namespace artweb\artbox\components\artboxtree\treegrid;

use artweb\artbox\ecommerce\models\TaxOption;
use Yii;
use yii\base\InvalidConfigException;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class TreeGridWidget extends \artweb\artbox\components\artboxtree\ArtboxTreeWidget {

    /**
     * @var array grid column configuration. Each array element represents the configuration
     * for one particular grid column.
     * @see \yii\grid::$columns for details.
     */
    public $columns = [];

    /**
     * @var string the default data column class if the class name is not explicitly specified when configuring a data column.
     * Defaults to 'leandrogehlen\treegrid\TreeGridColumn'.
     */
    public $dataColumnClass;

    /**
     * @var array the HTML attributes for the container tag of the grid view.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'table table-striped table-bordered'];

    /**
     * @var array The plugin options
     */
    public $pluginOptions = [];

    /**
     * @var boolean whether to show the grid view if [[dataProvider]] returns no data.
     */
    public $showOnEmpty = true;

    public $rowOptions = [];

    /**
     * @var \Closure an anonymous function that is called once BEFORE rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $beforeRow;

    /**
     * @var \Closure an anonymous function that is called once AFTER rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $afterRow;

    /**
     * @var boolean whether to show the header section of the grid table.
     */
    public $showHeader = true;

    /**
     * @var array the HTML attributes for the table header row.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $headerRowOptions = [];

    /**
     * @var boolean whether to show the footer section of the grid table.
     */
    public $showFooter = false;

    /**
     * @var string the HTML display when the content of a cell is empty
     */
    public $emptyCell = '&nbsp;';

    public $levelSymbol = '&ndash;';

    /**
     * Init the widget object.
     */
    public function init() {
        parent::init();

        $this->initColumns();
    }

    /**
     * Runs the widget.
     */
    public function run() {
        $run = parent::run();
        if (!is_null($run))
            return $run;

        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $pagination = $this->dataProvider->getPagination();
            $pagination->setPageSize($this->dataProvider->getTotalCount());

            $header = $this->showHeader ? $this->renderTableHeader() : false;
            $body = $this->renderItems();
            $footer = $this->showFooter ? $this->renderTableFooter() : false;

            $content = array_filter([
                $header,
                $body,
                $footer
            ]);

            return Html::tag('table', implode("\n", $content), $this->options);
        } else {
            return $this->renderEmptyResult();
        }
    }

    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column TreeGridColumn */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        return "<thead>\n" . $content . "\n</thead>";
    }

    /**
     * Renders the table footer.
     * @return string the rendering result.
     */
    public function renderTableFooter()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column TreeGridColumn */
            $cells[] = $column->renderFooterCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->footerRowOptions);
        return "<tfoot>\n" . $content . "\n</tfoot>";
    }

    /**
     * Renders the data models for the grid view.
     */
    public function renderItems()
    {
        $rows = [];
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $models = TaxOption::find()->normalizeTreeData($models, $this->rootParentId);
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }

            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }

        if (empty($rows)) {
            $colspan = count($this->columns);
            return "<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>";
        } else {
            return implode("\n", $rows);
        }
    }

    /**
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column TreeGridColumn */
        $i = 0;
        foreach ($this->columns as $column) {
            $cells[] = $column->renderDataCell($model, $key, $index, $i == 0, $this->levelSymbol);
            $i++;
        }
        if ($this->rowOptions instanceof \Closure) {
            $options = call_user_func($this->rowOptions, $model, $key, $index, $this);
        } else {
            $options = $this->rowOptions;
        }
        $options['data-key'] = is_array($key) ? json_encode($key) : (string) $key;

        $id = ArrayHelper::getValue($model, $this->keyNameId);
        Html::addCssClass($options, "treegrid-$id");

        $parentId = ArrayHelper::getValue($model, $this->keyNameParentId);
        if ($parentId) {
            Html::addCssClass($options, "treegrid-parent-$parentId");
        }

        return Html::tag('tr', implode('', $cells), $options);
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ? : TreeGridColumn::className(),
                    'grid' => $this,
                ], $column));
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * Creates a [[DataColumn]] object based on a string in the format of "attribute:format:label".
     * @param string $text the column specification string
     * @return DataColumn the column instance
     * @throws InvalidConfigException if the column specification is invalid
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
            'class' => $this->dataColumnClass ? : TreeGridColumn::className(),
            'grid' => $this,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    /**
     * This function tries to guess the columns to show from the given data
     * if [[columns]] are not explicitly specified.
     */
    protected function guessColumns()
    {
        $models = $this->dataProvider->getModels();
        $model = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $this->columns[] = $name;
            }
        }
    }
}
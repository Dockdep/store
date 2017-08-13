<?php
    
    namespace artweb\artbox\modules\catalog\models;
    
    use artweb\artbox\components\artboxtree\ArtboxTreeQueryTrait;
    use yii\db\ActiveQuery;
    
    /**
     * This is the ActiveQuery class for [[Category]].
     *
     * @see Category
     */
    class CategoryQuery extends ActiveQuery
    {
        use ArtboxTreeQueryTrait;
        
        /**
         * @inheritdoc
         * @return Category[]|array
         */
        public function all($db = null)
        {
            return parent::all($db);
        }
        
        /**
         * @inheritdoc
         * @return Category|array|null
         */
        public function one($db = null)
        {
            return parent::one($db);
        }
    }

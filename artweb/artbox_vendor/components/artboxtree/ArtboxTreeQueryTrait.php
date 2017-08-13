<?php
    
    namespace artweb\artbox\components\artboxtree;
    
    trait ArtboxTreeQueryTrait
    {
        
        public static $cacheTree = [];
        
        /** @var \yii\db\ActiveQuery $this */
        static $model;
        
        /*
         * @return \yii\db\ActiveQuery
         */
        private function getModel()
        {
            if(empty( self::$model )) {
                $class = $this->modelClass;
                self::$model = new $class;
            }
            return self::$model;
        }
        
        public function getTree($group = NULL, $with = NULL)
        {
            $model = $this->getModel();
            if($group !== NULL) {
                $this->andWhere([ $model->keyNameGroup => $group ]);
            }
            if($with) {
                $this->with($with);
            }
            $data = $this->all();
            if(empty( $data )) {
                return [];
            }
            
            return $this->buildTree($data);
        }
        
        private function recursiveRebuild($tree, $parentPath = NULL, $depth = 0)
        {
            $model = $this->getModel();
            
            foreach($tree as $row) {
                $path = ( is_null($parentPath) ? '' : $parentPath . $model->delimiter ) . $row[ 'item' ]->getAttribute($model->keyNameId);
                $row[ 'item' ]->setAttribute($model->keyNamePath, $path);
                $row[ 'item' ]->setAttribute($model->keyNameDepth, $depth);
                $row[ 'item' ]->save();
                if(!empty( $row[ 'children' ] )) {
                    $this->recursiveRebuild($row[ 'children' ], $path, $depth + 1);
                }
            }
        }
        
        /**
         * @param int $group
         */
        public function rebuildMP($group, $with = NULL)
        {
            $tree = $this->getTree($group, $with);
            
            $this->recursiveRebuild($tree);
        }
        
        protected function buildTree(array $data, $parentId = 0)
        {
            $model = $this->getModel();
            
            $result = [];
            foreach($data as $element) {
                if($element[ $model->keyNameParentId ] == $parentId) {
                    $children = $this->buildTree($data, $element[ $model->keyNameId ]);
                    $result[] = [
                        'item'     => $element,
                        'children' => $children,
                    ];
                }
            }
            return $result;
        }
        
        public function normalizeTreeData(array $data, $parentId = NULL)
        {
            $model = $this->getModel();
            
            $result = [];
            foreach($data as $element) {
                if($element[ $model->keyNameParentId ] == $parentId) {
                    $result[] = $element;
                    $children = $this->normalizeTreeData($data, $element[ $model->keyNameId ]);
                    if($children) {
                        $result = array_merge($result, $children);
                    }
                }
            }
            return $result;
        }
    }
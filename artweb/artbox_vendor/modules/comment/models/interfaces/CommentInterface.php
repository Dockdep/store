<?php
    
    namespace artweb\artbox\modules\comment\models\interfaces;
    
    use yii\data\ActiveDataProvider;
    
    /**
     * Interface CommentInterface
     * @package artweb\artbox\modules\comment\models\interfaces
     */
    interface CommentInterface
    {
        
        public function setEntity(string $entity);
        
        public function getEntity(): string;
        
        public function setEntityId(int $entityId);
        
        public function getEntityId(): int;
        
        public static function getTree(string $entity, int $entityId): ActiveDataProvider;
        
    }
<?php
    namespace frontend\models;
    

    use yii\base\Model;
    use Yii;

    use yii\elasticsearch\Query;

    /**
     * Signup form
     */
    class SearchForm extends Model
    {
        public $word;
        
        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [
                    [ 'word' ],
                    'required',
                ],
                [
                    [ 'word' ],
                    'string',
                    'min' => 3,
                ],
            ];
        }
        
        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'word' => Yii::t('app', 'Поиск'),
            ];
        }


        public function search($page = 0,$limit,$langId){
            $filterQuery = new Query();
            $filterQuery->source('*');
            $filterQuery->limit($limit);
            $filterQuery->from(Catalog::index(), Catalog::type());

            $filters = [];

            if (preg_match('/^\+?\d+$/', $this->word) && ( iconv_strlen($this->word) > 4 )) {

                $filterVO['nested']['path'] = 'variants';
                $filterVO['nested']['query']['bool']['filter'][]['match']['variants.sku'] = $this->word;
                $filters['bool']['must'][] = $filterVO;

            } else {


                $params = explode(' ', preg_replace("|[\s,.!:&?~();-]|i", " ", $this->word));


                if(iconv_strlen($this->word) >= 3 && !preg_match ('/^20[\d]{2}$/',$this->word, $matches)){
                    $filterVO['nested']['path'] = 'variants';
                    $filterVO['nested']['query']['bool']['should'][]['match']['variants.title'] = $this->word;
                    $filterVO['nested']['query']['bool']['should'][]['match']['variants.sku'] = $this->word;
                    $filters['bool']['should'][] = $filterVO;

                    $filters['bool']['should'][]['match_phrase']['brand.title'] = $this->word;

                    $filters['bool']['should'][]['match_phrase']['full_name'] = $this->word;


                    $filterC['nested']['path'] = 'categories';
                    $filterC['nested']['query']['bool']['should'][]['match_phrase']['categories.title'] = $this->word;
                    $filterC['nested']['query']['bool']['should'][]['match_phrase']['categories.category_synonym'] = $this->word;
                    $filters['bool']['should'][] = $filterC;

                }

           //     self::filterKeywords($params, $filters);



            }

            $filterQuery->query($filters);
            $filtersPos['bool']['must'][]['term']['language_id'] = $langId;
            $filterQuery->postFilter($filtersPos);


            $filterQuery->orderBy = ['stock' => ['order'=>'desc']];


            if($page){
                $offset = $limit*($page-1);
            } else {
                $offset = 0;
            }


            $filterQuery->offset($offset)->limit($limit);
            $command = $filterQuery->createCommand();
            $result = $command->search();

            return $result;
        }


        /**
         * Fill query with keywords filter
         *
         * @param array               $params
         * @param array               $filters
         */
        public static function filterKeywords(array $params, &$filters)
        {
            if (!empty( $params )) {
                if (!is_array($params)) {
                    $params = [ $params ];
                }
                /**
                 * @var string $param Inputed keyword
                 */
                foreach ($params as $param) {

                    if(iconv_strlen($param) >= 3 && !preg_match ('/^20[\d]{2}$/',$param, $matches)){
                        $filterVO['nested']['path'] = 'variants';
                        $filterVO['nested']['query']['bool']['should'][]['match_phrase']['variants.title'] = $param;
                        $filterVO['nested']['query']['bool']['should'][]['match_phrase']['variants.sku'] = $param;
                        $filters['bool']['should'][] = $filterVO;

                        $filterC['nested']['path'] = 'categories';
                        $filterC['nested']['query']['bool']['should'][]['match']['categories.title'] = $param;
                        $filterC['nested']['query']['bool']['should'][]['match']['categories.category_synonym'] = $param;
                        $filters['bool']['should'][] = $filterC;

                        $filters['bool']['should'][]['match']['full_name'] = $param;

                        $filters['bool']['should'][]['match_phrase']['brand.title'] = $param;

                    }

                }

            }
        }
    }

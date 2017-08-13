<?php
    namespace console\controllers;
    
    use artweb\artbox\models\Customer;
    use yii\console\Controller;
    use yii\db\Connection;
    use yii\db\Query;
    use yii\helpers\Console;
    
    class UserMigrateController extends Controller
    {
        public function actionIndex()
        {
            
            $mysql = $this->generateConnection();
            
            $inserted_ids = Customer::find()
                                    ->select('remote_id')
                                    ->where(
                                        [
                                            'not',
                                            [ 'remote_id' => null ],
                                        ]
                                    )
                                    ->column();
            $query = ( new Query() )->from('zlo_users')
                                    ->where(
                                        [
                                            'not',
                                            [
                                                'or',
                                                [ 'email' => null ],
                                                [
                                                    '<',
                                                    'char_length(email)',
                                                    1,
                                                ],
                                            ],
                                        ]
                                    )
                                    ->andWhere(
                                        [
                                            'not',
                                            [
                                                'or',
                                                [ 'pass' => null ],
                                                [
                                                    '<',
                                                    'char_length(pass)',
                                                    6,
                                                ],
                                            ],
                                        ]
                                    )
                                    ->andFilterWhere(
                                        [
                                            'not',
                                            [ 'id' => $inserted_ids ],
                                        ]
                                    )
                                    ->orderBy('id');
            $total_count = 0;
            $time_begin = time();
            foreach ($query->batch(100, $mysql) as $batch) {
                echo "Полученно пакет из " . count($batch) . " элементов.\n";
                $insert = [];
                foreach ($batch as $index => $item) {
                    $insert[] = $this->generateRow($item);
                    unset( $batch[ $index ] );
                }
                $count = \Yii::$app->db->createCommand()
                                       ->batchInsert(
                                           'customer',
                                           [
                                               'username',
                                               'password_hash',
                                               'auth_key',
                                               'phone',
                                               'gender',
                                               'email',
                                               'status',
                                               'created_at',
                                               'updated_at',
                                               'birthday',
                                               'city',
                                               'address',
                                               'remote_id',
                                           ],
                                           $insert
                                       )
                                       ->execute();
                $total_count += $count;
                $count = $this->ansiFormat($count, Console::BOLD);
                echo "Количество вставленных строк: $count\n";
            }
            $total_count = $this->ansiFormat($total_count, Console::BOLD);
            echo "Итого импортировано строк: $total_count за " . ( time() - $time_begin ) . "\n";
            return 0;
        }
        
        public function actionProduct()
        {
            $mysql = $this->generateConnection();
            
            $query = ( new Query() )->from('catalogs_products')
                                    ->where(
                                        [
                                            'not',
                                            [
                                                'or',
                                                [ 'name' => null ],
                                                [
                                                    '<',
                                                    'char_length(name)',
                                                    1,
                                                ],
                                            ],
                                        ]
                                    )
                                    ->indexBy('id');
            foreach ($query->batch(100, $mysql) as $batch) {
                foreach ($batch as $item) {
                    $count = \Yii::$app->db->createCommand()
                                           ->update(
                                               'product',
                                               [
                                                   'remote_id' => $item[ 'id' ],
                                               ],
                                               [
                                                   'id' => ( new Query() )->select(
                                                       [ 'product.id' ],
                                                       'DISTINCT ON (product.id)'
                                                   )
                                                                          ->from('product')
                                                                          ->innerJoin(
                                                                              'product_lang',
                                                                              'product.id = product_lang.product_id'
                                                                          )
                                                                          ->where([ 'product_lang.language_id' => 2 ])
                                                                          ->andWhere(
                                                                              [ 'product_lang.title' => $item[ 'name' ] ]
                                                                          )
                                                                          ->limit(1),
                                               ]
                                           )
                                           ->execute();
                    echo "Tried to UPDATE product with remote_id " . $item[ 'id' ] . " and name " . $item[ 'name' ] . ", affected " . $count . " row \n";
                }
            }
        }
        
        public function actionVideo()
        {
            $query = ( new Query() )->from('product_video')
                                    ->where(
                                        [
                                            'not',
                                            [
                                                'ilike',
                                                'url',
                                                'https://',
                                            ],
                                        ]
                                    )
                                    ->orWhere([ 'title' => null ]);
            foreach ($query->each() as $video) {
                $this->updateRow($video);
            }
            return 0;
        }
        
        public function actionClearUser()
        {
            $subquery = ( new Query() )->select(
                [
                    'email',
                    'count_email' => 'COUNT(*)',
                ]
            )
                                       ->from('customer')
                                       ->groupBy('email');
            $query = ( new Query() )->from([ 'temp' => $subquery ])
                                    ->where(
                                        [
                                            '>',
                                            'count_email',
                                            1,
                                        ]
                                    );
            $email_list = $query->column();
            foreach ($email_list as $email) {
                $duplicates = array_slice(
                    ( new Query() )->select('id')
                                   ->from('customer')
                                   ->where([ 'email' => $email ])
                                   ->orderBy([ 'id' => SORT_ASC ])
                                   ->column(),
                    1
                );
                $rows = \Yii::$app->db->createCommand()
                                      ->delete('customer', [ 'id' => $duplicates ])
                                      ->execute();
                echo $rows . " duplicate emails were processed\n";
            }
            return 0;
        }
        
        private function updateRow(array $row)
        {
            $insert = [];
            if (empty( $row[ 'title' ] )) {
                $insert[ 'title' ] = 'video-' . $row[ 'id' ];
            }
            if (strpos($row[ 'url' ], 'http://') !== false) {
                $insert[ 'url' ] = str_replace('http://', 'https://', $row[ 'url' ]);
            } elseif (strpos($row[ 'url' ], 'src="//') !== false) {
                $insert[ 'url' ] = str_replace('src="//', 'src="https://', $row[ 'url' ]);
            }
            if (mb_strlen($insert[ 'url' ]) > 255) {
                \Yii::$app->db->createCommand()
                              ->delete('product_video', [ 'id' => $row[ 'id' ] ])
                              ->execute();
                echo "Product video with id {$row['id']} deleted (too long name)\n";
                return;
            }
            if (!empty( $insert )) {
                \Yii::$app->db->createCommand()
                              ->update(
                                  'product_video',
                                  $insert,
                                  [ 'id' => $row[ 'id' ] ]
                              )
                              ->execute();
                echo "Product video with id {$row['id']} replaced\n";
            } else {
                echo "Product video with id {$row['id']} skipped\n";
            }
        }
        
        private function generateRow(array $item): array
        {
            $time_begin = time();
            $result = [];
            if (!empty( $item[ 'username' ] )) {
                $result[] = $item[ 'username' ];
            } else {
                $result[] = '';
            }
            $result[] = \Yii::$app->security->generatePasswordHash($item[ 'pass' ]);
            $result[] = \Yii::$app->security->generateRandomString();
            if (!empty( $item[ 'tel' ] )) {
                $result[] = $item[ 'tel' ];
            } else {
                $result[] = null;
            }
            if ($item[ 'sex' ] == 'M') {
                $result[] = 'Мужской';
            } elseif ($item[ 'sex' ] == 'W') {
                $result[] = 'Женский';
            } else {
                $result[] = '';
            }
            $result[] = $item[ 'email' ];
            if ($item[ 'active' ] == 1) {
                $result[] = 10;
            } else {
                $result[] = 0;
            }
            $time = time();
            if (!empty( $item[ 'registered' ] )) {
                $time = strtotime($item[ 'registered' ]) ? : time();
            }
            $result[] = $time;
            $result[] = $time;
            if (!empty( $item[ 'birthday' ] )) {
                $result[] = strtotime($item[ 'birthday' ]) ? : null;
            } else {
                $result[] = null;
            }
            if (!empty( $item[ 'city' ] )) {
                $result[] = $item[ 'city' ];
            } else {
                $result[] = null;
            }
            if (!empty( $item[ 'address' ] )) {
                $result[] = $item[ 'address' ];
            } else {
                $result[] = null;
            }
            $result[] = $item[ 'id' ];
            $passed = time() - $time_begin;
            echo "Обработан элемент с ID " . $item[ 'id' ] . " за " . $passed . "\n";
            return $result;
        }
        
        private function generateConnection(): Connection
        {
            return new Connection(
                [
                    'dsn'      => 'mysql:host=195.248.225.119;dbname=extremstyle',
                    'username' => 'extremstyle',
                    'password' => 'Ry4PWmM6GCp3UCTf',
                    'charset'  => 'utf8',
                ]
            );
        }
    }
<?php
    
    namespace artweb\artbox\ecommerce\controllers;
    
    use artweb\artbox\ecommerce\models\Label;
    use artweb\artbox\ecommerce\models\Order;
    use common\models\User;
    use yii\data\ActiveDataProvider;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\web\Controller;
    use yii\web\Response;
    
    class StatisticsController extends Controller
    {
        public function actionIndex($date_range = NULL, $label = NULL, $manager = NULL)
        {
            /**
             * Get a dates range
             */
            if (!empty($date_range)) {
                $arr = [];
                preg_match('@(.*)\s:\s(.*)@', $date_range, $arr);
                $dateFilter = [
                    'between',
                    'created_at',
                    strtotime($arr[ 1 ]),
                    strtotime($arr[ 2 ]),
                ];
            } else {
                $dateFilter = [];
            }
            
            if (!empty($label)) {
                $labelFilter = [ 'label' => $label ];
            } else {
                $labelFilter = [];
            }
            
            if (!empty($manager)) {
                $managerFilter = [ 'manager_id' => $manager ];
            } else {
                $managerFilter = [];
            }
            
            /**
             * Get labels
             */
            $labels = Label::find()
                           ->with('lang')
                           ->all();
            
            /**
             * Get user
             */
            $managers = User::find()
                            ->all();
            
            /**
             * Generate statistics
             */
            $labelStatistics = ArrayHelper::map(
                $labels,
                function(Label $model) {
                    return $model->lang->title;
                },
                function(Label $model) use ($dateFilter, $managerFilter) {
                    return $model->getStatistics($dateFilter, $managerFilter);
                }
            );
            
            /**
             * Data provider for table
             */
            $dataProvider = new ActiveDataProvider(
                [
                    'query' => Order::find()
                                    ->filterWhere($dateFilter)
                                    ->andFilterWhere($managerFilter)
                                    ->andFilterWhere($labelFilter),
                ]
            );
            
            /**
             * Creating charts data
             */
            $labelChartData1 = [
                'labels'   => array_keys($labelStatistics),
                'datasets' => [
                    [
                        'label'           => 'Заказов, шт.',
                        'data'            => ArrayHelper::getColumn($labelStatistics, 'count', false),
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                        'borderColor'     => 'rgba(54, 162, 235, 1)',
                        'borderWidth'     => 1,
                    ],
                ],
            ];
            
            $labelChartData2 = [
                'labels'   => array_keys($labelStatistics),
                'datasets' => [
                    [
                        'label'           => 'На сумму, грн.',
                        'data'            => ArrayHelper::getColumn($labelStatistics, 'sum', false),
                        'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                        'borderColor'     => 'rgba(255,99,132,1)',
                        'borderWidth'     => 1,
                    ],
                ],
            ];
            
            $labelChartData3 = [
                'labels'   => array_keys($labelStatistics),
                'datasets' => [
                    [
                        'label'           => 'Заказано товаров, шт.',
                        'data'            => ArrayHelper::getColumn($labelStatistics, 'products', false),
                        'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                        'borderColor'     => 'rgba(255, 206, 86, 1)',
                        'borderWidth'     => 1,
                    ],
                    [
                        'label'           => 'Уникальных товаров, шт.',
                        'data'            => ArrayHelper::getColumn($labelStatistics, 'unique', false),
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'borderColor'     => 'rgba(75, 192, 192, 1)',
                        'borderWidth'     => 1,
                    ],
                ],
            ];
            
            /**
             * Getting rejection statistics
             */
            $rejectStatistics = Order::getRejectionStatistics($dateFilter, $managerFilter);
            
            /**
             * Charts data for rejects
             */
            $rejectChartData1 = [
                'labels'   => array_keys($rejectStatistics),
                'datasets' => [
                    [
                        'label'           => 'Заказов, шт.',
                        'data'            => ArrayHelper::getColumn($rejectStatistics, 'count', false),
                        'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                        'borderColor'     => 'rgba(153, 102, 255, 1)',
                        'borderWidth'     => 1,
                    ],
                ],
            ];
            
            $rejectChartData2 = [
                'labels'   => array_keys($rejectStatistics),
                'datasets' => [
                    [
                        'label'           => 'На сумму, грн.',
                        'data'            => ArrayHelper::getColumn($rejectStatistics, 'sum', false),
                        'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                        'borderColor'     => 'rgba(255, 159, 64, 1)',
                        'borderWidth'     => 1,
                    ],
                ],
            ];
            
            return $this->render(
                'index',
                [
                    'labels'              => $labels,
                    'managers'            => $managers,
                    'labelStatistics'     => $labelStatistics,
                    'rejectionStatistics' => $rejectStatistics,
                    'dataProvider'        => $dataProvider,
                    'labelChartData1'     => $labelChartData1,
                    'labelChartData2'     => $labelChartData2,
                    'labelChartData3'     => $labelChartData3,
                    'rejectChartData1'    => $rejectChartData1,
                    'rejectChartData2'    => $rejectChartData2,
                    'dateValue'           => empty($date_range) ? '' : $date_range,
                    'dataLabel'           => empty($label) ? false : $label,
                    'dataManager'         => empty($manager) ? false : $manager,
                ]
            );
        }
        
        public function actionExport($date_range = NULL, $label = NULL, $manager = NULL)
        {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            
            $formatter = \Yii::$app->formatter;
            /**
             * Get export options
             */
            if (!empty($date_range)) {
                $arr = [];
                preg_match('@(.*)\s:\s(.*)@', $date_range, $arr);
                $dateFilter = [
                    'between',
                    'created_at',
                    strtotime($arr[ 1 ]),
                    strtotime($arr[ 2 ]),
                ];
            } else {
                $dateFilter = [];
            }
            
            if (!empty($label)) {
                $labelFilter = [ 'label' => $label ];
            } else {
                $labelFilter = [];
            }
            
            if (!empty($manager)) {
                $managerFilter = [ 'manager_id' => $manager ];
            } else {
                $managerFilter = [];
            }
            
            $orders = Order::find()
                           ->with(
                               [
                                   'products',
                                   'orderLabel',
                                   'manager',
                               ]
                           )
                           ->filterWhere($dateFilter)
                           ->andFilterWhere($labelFilter)
                           ->andFilterWhere($managerFilter)
                           ->all();
            
            $file = fopen(\Yii::getAlias('@storage/') . 'statistics_export.csv', 'w');
            foreach ($orders as $order) {
                $line = [];
                /**
                 * @var Order $order
                 */
                $line[] = (string) $order->id;
                $line[] = $formatter->asDatetime($order->created_at);
                $line[] = (string) $order->name;
                if (empty($order->products)) {
                    $line[] = '';
                } else {
                    $i = 0;
                    $products = '';
                    foreach ($order->products as $product) {
                        $i++;
                        $products .= $product->sku;
                        if (count($order->products) != $i) {
                            $products .= ' ,';
                        }
                    }
                    $line[] = $products;
                }
                $line[] = (string) $order->city;
                $line[] = (string) $order->orderLabel->label;
                $line[] = (string) $order->total;
                $line[] = empty($order->reason) ? '' : $formatter->asText(Order::REASONS[ $order->reason ]);
                $line[] = empty($order->manager) ? '' : $formatter->asText($order->manager->username);
                $line[] = str_replace(
                    [
                        "\r\n",
                        "\n",
                        "\r",
                    ],
                    ' ',
                    $order->body
                );
                
                fputcsv($file, $line, ";");
            }
            fclose($file);
            
            return [
                'message' => 'Файл успешно сгенерирован',
                'button'  => Html::a(
                    Html::tag(
                        'i',
                        '',
                        [
                            'class' => 'glyphicon glyphicon-download-alt',
                        ]
                    ) . ' Скачать',
                    '/storage/statistics_export.csv',
                    [
                        'class' => 'btn bg-olive',
                    ]
                ),
            ];
        }
    }

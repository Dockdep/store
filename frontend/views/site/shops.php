<?php
    use artweb\artbox\models\Page;
    use yii\helpers\Html;
    use yii\web\View;
    
    /**
     * @var View $this
     * @var Page $model
     */
    $this->title = $model->lang->title;
    $this->params[ 'breadcrumbs' ][] = [
        'label'    => Html::tag(
            'span',
            $this->title,
            [
                'itemprop' => 'name',
            ]
        ),
        'template' => "<li itemscope itemprop='itemListElement' itemtype='http://schema.org/ListItem'>{link}<meta itemprop='position' content='2' /></li>\n",
    ];
    echo $model->lang->body;
    
    $js = <<< JS
var hash = window.location.hash;
switch (hash) {
    case '#4':
    var element = $('[data-index=2]');
    element.trigger('click');
    break;
    case '#5':
    var element = $('[data-index=3]');
    element.trigger('click');
    break;
    case '#2':
    var element = $('[data-index=4]');
    element.trigger('click');
    break;
    case '#6':
    var element = $('[data-index=5]');
    element.trigger('click');
    break;
    }
JS;
    
    $this->registerJs($js, View::POS_READY);
    
    $js = <<< JS
var shopPage = $('.title_shops').length
    if(shopPage>0){
        mapShops();
    }
    function mapShops() {
        onload = function() {
            initialize()
        }
        var cityNum = $('#hidden_shops ul li.active').data('index')

        function initialize() {

            if(cityNum==1){
                var start_position = new google.maps.LatLng('50.4501', '30.5234');
                function markerTest() {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng('50.46235', '30.4984009'),
                        map: map,
                        title: 'Киев, ул Глубочицкая, 53',
                        icon: image1
                    });


                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng('50.4061871', '30.613178'),
                        map: map,
                        title: 'Киев,(Осокорки) Днепровская Набережная, 23а',
                        icon: image1
                    });
                }
            }
            if(cityNum==2){
                var start_position = new google.maps.LatLng('50.0361468', '36.2190988');
                function  markerTest() {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng('50.0361468', '36.2190988'),
                        map: map,
                        title: 'Харьков, пр. Науки, 45/2. Cт.метро 23 августа',
                        icon: image1
                    });
                }
            }
            if(cityNum==3){
                var start_position = new google.maps.LatLng('46.431972', '30.728785');
                function  markerTest(){
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng('46.431972', '30.728785'),
                        map: map,
                        title: 'Одесса, ул. Краснова,12 ',
                        icon: image1
                    });
                }
            }
            if(cityNum==4){
                var start_position = new google.maps.LatLng('48.4206839', '35.0667819');
                function markerTest() {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng('48.4206839', '35.0667819'),
                        map: map,
                        title: 'Днепр, ул. Набережная Победы, 118',
                        icon: image1
                    });
                }
            }
            if(cityNum==5){
                var start_position = new google.maps.LatLng('48.3596745', '24.4078794');
                function markerTest(){
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng('48.3596745', '24.4078794'),
                        map: map,
                        title: 'ГК Буковель, Паркинг №1 (1 этаж)',
                        icon: image1
                    });
                }
            }



            if(cityNum==1){
                var settings = {
                    zoom:11,
                    //                scrollwheel: false,
                    center: start_position,
                    mapTypeControl: true,
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                    navigationControl: true,
                    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
                    scaleControl: true,
                    streetViewControl: true,
                    rotateControl: true,
                    zoomControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
            } else {
                var settings = {
                    zoom:18,
                    //                scrollwheel: false,
                    center: start_position,
                    mapTypeControl: true,
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
                    navigationControl: true,
                    navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
                    scaleControl: true,
                    streetViewControl: true,
                    rotateControl: true,
                    zoomControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
            }


            var map = new google.maps.Map(document.getElementById("map_canvas"), settings);

            var image1 = new google.maps.MarkerImage('/images/markers/marker-we-1.png',
                new google.maps.Size(32, 32),
                new google.maps.Point(0,0),
                new google.maps.Point(32, 32)
            );

            markerTest()
        }
        $('#hidden_shops li').click(function () {
            cityNum = $(this).data('index')
            initialize()
        })
    }
JS;
    
    $this->registerJs($js, View::POS_READY);

?>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>

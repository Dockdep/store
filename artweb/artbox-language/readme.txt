Как включить мультиязычность на сайте:
1. Запускаем миграцию: php yii migrate --migrationPath=common/modules/language/migrations
2. Добавляем в файл конфигурации:
'urlManager' => [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'class'=>'artweb\artbox\language\components\LanguageUrlManager',
    'rules'=>[
        '/' => 'site/index',
        '<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
    ]
],
3. Добавляем в файл конфигурации:
'request' => [
    'class' => 'artweb\artbox\language\components\LanguageRequest'
],
4. Добавляем в файл конфигурации:
'language'=>'ru-RU',
'i18n' => [
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@frontend/messages',
            'sourceLanguage' => 'en',
            'fileMap' => [
            ],
        ],
    ],
],
5. Переводы писать в файл frontend\messages\{language}\app.php, где {language} - нужный язык, например ru.
6. Для вывода на странице сообщения с переводом используем функцию: Yii::t('app', {message}, $params = [], $language = null),
    где {message} - нужное сообщение, $params - массив параметров, $language - нужный язык (по умолчанию используется текущий язык).
7. В наличие также виджет переключения языка: LanguagePicker::widget()


Как использовать мультиязычность для Active Record:
1. Создаем для таблицы {table} таблицу с языками {table_lang}.
2. Создаем для класса {Table} класс с языками {TableLang}.
3. Подключаеи для класса {Table} поведение LanguageBehavior:
public function behaviors() {
    return [
        'language' => [
            'class' => LanguageBehavior::className(),
            'objectLang' => {TableLang}::className() // optional, default to {TableLang}::className()
            'ownerKey' => {Table}->id //optional, default to {Table}->primaryKey()[0]
            'langKey' => {TableLang}->table_id //optional, default to {Table}->tableName().'_id'
        ],
    ];
}
3.1. PHPDoc для {Table}:
     * * From language behavior *
     * @property {TableLang}    $lang
     * @property {TableLang}[]  $langs
     * @property {TableLang}    $objectLang
     * @property string         $ownerKey
     * @property string         $langKey
     * @property {TableLang}[]  $modelLangs
     * @property bool           $transactionStatus
     * @method string           getOwnerKey()
     * @method void             setOwnerKey(string $value)
     * @method string           getLangKey()
     * @method void             setLangKey(string $value)
     * @method ActiveQuery      getLangs()
     * @method ActiveQuery      getLang( integer $language_id )
     * @method {TableLang}[]    generateLangs()
     * @method void             loadLangs(Request $request)
     * @method bool             linkLangs()
     * @method bool             saveLangs()
     * @method bool             getTransactionStatus()
     * * End language behavior *
3.2. Убрать language behavior с наследуемых таблиц от {Table} ({TableSearch}...)
4. Доступные полезные методы:
    {Table}->getLangs() - получить все текущие {TableLang} для {Table} проиндексированные по language_id
    {Table}->getLang($language_id = NULL) - получить {TableLang} для определенного языка (default: текущий язык) для {Table}
    {Table}->generateLangs() - получить массив {TableLang} под каждый язык, включая существующие записи, для {Table}
    {Table}->loadLangs($request) - заполнить массив {TableLang} данными с POST
    {Table}->linkLangs() - связать каждый элемент массива {TableLang} с текущей {Table}
    {Table}->saveLangs() - провалидировать и сохранить каждый элемент массива {TableLang}
5. Добавить поля в форму (к примеру через Bootstrap Tabs).
    В наличии:
    LanguageForm::widget([
        'modelLangs'   => {TableLang}[],
        'formView'      => string,
        'form'          => ActiveForm,
    ]);
6. Обрабатывать данные в контроллере.
    1. После создания/поиска {Table} создаем/находим языковые модели {Table}->generateLangs()
    2. При POST запросе загружаем данные в языковые модели {Table}->loadLangs(Request $request)
    3. После сохранения, если транзанкция успешна, то свойство {Table}->transactionStatus будет true, иначе возникла ошибка в какой то модели.
7. Получать данные на публичной части сайта через {Table}->lang.

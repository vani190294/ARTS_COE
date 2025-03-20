<?php
$db_change = 'arts_feb21';
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname='.$db_change,
    'username' => $params['user_name'],
    'password' => $params['user_pass'],
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'coeDBcache',
];	
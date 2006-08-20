<?php
$config = array();

$config['Test']['include_path'] = dirname(dirname(__FILE__));

$config['Solar']['ini_set']['error_reporting'] = E_ALL | E_STRICT;
$config['Solar']['ini_set']['display_errors'] = true;
$config['Solar']['ini_set']['date.timezone'] = 'America/Chicago';

$config['Solar_Test_Example'] = array(
	'zim' => 'gaz',
);

$config['Solar_Debug_Var']['output'] = 'text';

$config['Solar_Sql_Adapter_Sqlite'] = array(
    'name' => ':memory:',
);

$config['Solar_Sql_Adapter_Mysql'] = array(
    'name'   => 'test',
    'user'   => null,
    'pass'   => null,
    'host'   => '127.0.0.1',
);

$config['Test_Solar_Cache_Adapter_Apc']['run'] = false;
$config['Test_Solar_Cache_Adapter_Eaccellerator']['run'] = false;
$config['Test_Solar_Cache_Adapter_Memcache']['run'] = false;
$config['Test_Solar_Cache_Adapter_Xcache']['run'] = false;

return $config;
?>
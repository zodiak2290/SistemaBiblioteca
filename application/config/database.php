<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$ci =& get_instance();
$active_group = 'default';
$query_builder = TRUE;
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $ci->config->item('dbserver'),
	'username' => $ci->config->item('dbuser'),
	'password' => $ci->config->item('dbpass'),
	'database' => $ci->config->item('dbname'),
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'autoinit' => TRUE,//se modifico para evitar el error de inicio
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);




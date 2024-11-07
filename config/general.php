<?php
use Core\Config;
use Core\Db\Db;

Db::set('mysql', '3306', 'app', 'utf8mb4', 'root', 'password');

Config::$dev = true;

Config::$protocol = 'http';
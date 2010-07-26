<?php

include 'Registry.php';

Registry::add('foo', 'abc');

var_dump(Registry::get('foo'));

Registry::set('foo', '000');

var_dump(Registry::get('foo'));

var_dump(Registry::isImmutable('foo'));

var_dump(Registry::exists('foo'));

var_dump('---');

Registry::add('1');
var_dump(Registry::exists('1'));
Registry::delete('1');
var_dump(Registry::exists('1'));
Registry::add('1');
var_dump(Registry::exists('1'));

var_dump('---');

Registry::add('2');
var_dump(Registry::exists('2'));
Registry::invalidate('2');
var_dump(Registry::exists('2'));
//Registry::add('2');
//var_dump(Registry::exists('2'));
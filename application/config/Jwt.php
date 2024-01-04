<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$config['jwt_key'] = '95267fb2411bcd1291deddc861c5efa229c2a29e5e7e9446800bd65a23c65755';
$config['jwt_algorithm'] = 'HS256';
$config['jwt_issuer'] = 'https://serverprovider.com';
$config['jwt_audience'] = 'https://serverclient.com';
$config['jwt_expire'] = 3600;

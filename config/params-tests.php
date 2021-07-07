<?php

return [
    'server' => 'news_parser_1',
    'x-api-key' => '123321',
    'jwt' => [
        'key' => '123321',
        'accessExpire' => 60 * 60 * 24,
        'refreshExpire' => 60 * 60 * 24 * 90,
        'algorithm' => ['HS256']
    ],
];

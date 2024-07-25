<?php
return [
    'adminEmail' => 'send-codi-way@yandex.ru',
    'emailTo' => 'send-codi-way@yandex.ru',
    'domain' => 'http://berkut.dev-01.ru/',
    'default_image' => '/images/no-image.svg',
    'adminEmail' => 'send-codi-way@yandex.ru',
    'supportEmail' => 'send-codi-way@yandex.ru',
    'senderEmail' => 'send-codi-way@yandex.ru',
    'senderName' => 'Faamo',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'basket.calculator.price_types' => [
        'price' => [
            'max' => 24999, 
            'next' => 'small_wholesale_price',
            'discount' => 0,
            'title' => 'Розница',
        ],
        'small_wholesale_price' => [
            'max' => 79999, 
            'next' => 'wholesale_price',
            'discount' => 10,
            'title' => 'Мелкий опт',
        ],
        'wholesale_price' => [
            'discount' => 25,
            'title' => 'Опт',
        ],
    ],
    'order-create' => [
        'default' => [
            'payment_id'  => 2, // Дефолтное значение для способа оплаты
            'delivery_id' => 2, // Дефолтное значение для способа доставки
        ]
    ]
];

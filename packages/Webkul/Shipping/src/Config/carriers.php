<?php

return [
    'flatrate' => [
        'code'         => 'flatrate',
        'title'        => 'Flat Rate',
        'description'  => 'Flat Rate Shipping',
        'active'       => true,
        'default_rate' => '10',
        'type'         => 'per_unit',
        'class'        => 'Webkul\Shipping\Carriers\FlatRate',
    ],

    'free' => [
        'code'         => 'free',
        'title'        => 'Free Shipping',
        'description'  => 'Free Shipping',
        'active'       => true,
        'default_rate' => '0',
        'class'        => 'Webkul\Shipping\Carriers\Free',
    ],
    'storepickup' => [     // Nueva entrada para recogida en tienda
        'code'         => 'storepickup',
        'title'        => 'Pick up in shop',
        'description'  => 'Pick up your order in our shop.',
        'active'       => true,
        'default_rate' => '0',  
        'class'        => 'Webkul\Shipping\Carriers\StorePickup',
    ],
];

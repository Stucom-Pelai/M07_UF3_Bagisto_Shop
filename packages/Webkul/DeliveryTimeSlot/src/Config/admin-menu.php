<?php

return [
    [
        'key'   => 'core_deliverytimeslot',
        'name'  => 'delivery-time-slot::app.admin.delivery-time-slot.title',
        'route' => 'admin.timeslot.index',
        'sort'  =>  3,
        'icon'  => 'delivery-time-slot-icon',
    ], [
        'key'   => 'core_deliverytimeslot.deliverytimeslot',
        'name'  => 'delivery-time-slot::app.admin.delivery-time-slot.default-delivery-time',
        'route' => 'admin.timeslot.index',
        'sort'  => 1,
    ],[
        'key'   => 'core_deliverytimeslot.orderslots',
        'name'  => 'delivery-time-slot::app.admin.delivery-time-slot.delivery-orders',
        'route' => 'admin.timeslot.delivery.orders',
        'sort'  => 3,
    ],
];
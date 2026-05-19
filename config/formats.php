<?php

return [
    'acme_customers' => [
        'customer_id' => ['type' => 'int', 'required' => true],
        'registered_at' => ['type' => 'date', 'required' => true],
        'email' => ['type' => 'email', 'required' => false],
        'username' => ['type' => 'string', 'max' => 100, 'required' => false],
    ],
];
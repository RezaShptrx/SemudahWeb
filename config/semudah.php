<?php

return [
    'company' => [
        'name' => env('COMPANY_NAME', 'SEMUDAH'),
        'phone' => env('COMPANY_PHONE', ''),
        'address' => env('COMPANY_ADDRESS', ''),
        'email' => env('COMPANY_EMAIL', ''),
    ],
    
    'files' => [
        'auto_delete_enabled' => env('AUTO_DELETE_FILES', true),
        'delete_after_days' => env('FILE_DELETE_AFTER_DAYS', 7),
        'allowed_extensions' => ['pdf', 'docx', 'pptx', 'jpg', 'jpeg', 'png'],
        'max_file_size' => 10 * 1024 * 1024, // 10MB
    ],
    
    'payment' => [
        'default_method' => 'qris',
        'methods' => ['qris', 'gopay', 'shopeepay', 'virtual_account', 'transfer_bank', 'tunai'],
    ],
    
    'order' => [
        'number_prefix' => 'SMDH',
        'status_list' => [
            'menunggu_pembayaran',
            'menunggu_diproses',
            'sedang_diproses',
            'sedang_dicetak',
            'sedang_dibuat',
            'selesai',
            'sudah_diambil',
            'dibatalkan',
        ],
    ],
];

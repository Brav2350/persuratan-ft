<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Format Nomor Surat
    |--------------------------------------------------------------------------
    */
    'unit_code' => env('SURAT_UNIT_CODE', 'FT'),
    'number_suffix' => env('SURAT_NUMBER_SUFFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Disk Storage
    |--------------------------------------------------------------------------
    */
    'pdf_disk' => env('SURAT_PDF_DISK', 'local'),
    'signature_disk' => env('SURAT_SIGNATURE_DISK', 'local'),
    'template_disk' => env('SURAT_TEMPLATE_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Notifikasi
    |--------------------------------------------------------------------------
    */
    'notification_email_enabled' => env('SURAT_NOTIFY_EMAIL', false),

    /*
    |--------------------------------------------------------------------------
    | Logo & QR pada Dokumen PDF
    |--------------------------------------------------------------------------
    |
    | Path logo relatif terhadap folder public/. Tinggi/ukuran dalam pixel.
    |
    */
    'logo_header' => env('SURAT_LOGO_HEADER', 'images/logo-header.png'),
    'logo_footer' => env('SURAT_LOGO_FOOTER', 'images/logo-footer.png'),
    'logo_header_height' => env('SURAT_LOGO_HEADER_HEIGHT', 70),
    'logo_footer_height' => env('SURAT_LOGO_FOOTER_HEIGHT', 60),
    'qr_size' => env('SURAT_QR_SIZE', 60),
    'auto_number_enabled' => env('SURAT_AUTO_NUMBER_ENABLED', true),
    'email_domain' => env('SURAT_EMAIL_DOMAIN', 'tsu.ac.id'),
    'otp_expires_minutes' => env('SURAT_OTP_EXPIRES', 10),
    'otp_max_attempts' => env('SURAT_OTP_ATTEMPTS', 5),
];
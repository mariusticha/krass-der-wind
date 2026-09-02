<?php

declare(strict_types=1);

$emails = explode(',', (string) env('ADMIN_EMAILS', ''));

return [
    'emails' => array_values(array_unique(array_filter(array_map(
        static fn(string $email): string => strtolower(trim($email)),
        $emails,
    ), static fn(string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false))),
];

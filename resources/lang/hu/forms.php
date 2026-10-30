<?php

declare(strict_types=1);

return [
    'navigation_group' => 'Űrlapok',
    'navigation_label' => 'Űrlapok',
    'model_label' => 'Űrlap',
    'plural_model_label' => 'Űrlapok',
    'sections' => [
        'details' => 'Adatok',
        'fields' => 'Mezők',
    ],
    'fields' => [
        'title' => 'Cím',
        'slug' => 'URL-azonosító',
        'description' => 'Leírás',
        'success_message' => 'Köszönő üzenet',
        'notification_email' => 'Értesítési e-mail',
        'notification_email_helper' => 'Minden új beküldésről ide megy levél.',
        'is_active' => 'Aktív',
    ],
    'form_fields' => [
        'name' => 'Mezőnév (technikai)',
        'label' => 'Felirat',
        'type' => 'Típus',
        'options' => 'Lehetőségek (soronként egy)',
        'required' => 'Kötelező',
        'placeholder' => 'Helykitöltő szöveg',
    ],
    'field_types' => [
        'text' => 'Szöveg',
        'email' => 'E-mail',
        'phone' => 'Telefonszám',
        'textarea' => 'Hosszú szöveg',
        'select' => 'Legördülő lista',
        'checkbox' => 'Jelölőnégyzet',
        'radio' => 'Választógomb',
        'file' => 'Fájlfeltöltés',
        'date' => 'Dátum',
        'number' => 'Szám',
    ],
    'table' => [
        'title' => 'Cím',
        'slug' => 'URL-azonosító',
        'submissions' => 'Beküldések',
        'is_active' => 'Aktív',
        'created_at' => 'Létrehozva',
    ],
];

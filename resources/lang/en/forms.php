<?php

declare(strict_types=1);

return [
    'navigation_group' => 'Forms',
    'navigation_label' => 'Forms',
    'model_label' => 'Form',
    'plural_model_label' => 'Forms',
    'sections' => [
        'details' => 'Details',
        'fields' => 'Fields',
    ],
    'fields' => [
        'title' => 'Title',
        'slug' => 'URL Slug',
        'description' => 'Description',
        'success_message' => 'Success Message',
        'notification_email' => 'Notification Email',
        'notification_email_helper' => 'Receive notification emails for new submissions',
        'is_active' => 'Active',
    ],
    'form_fields' => [
        'name' => 'Field Name (technical)',
        'label' => 'Label',
        'type' => 'Type',
        'options' => 'Options (one per line)',
        'required' => 'Required',
        'placeholder' => 'Placeholder',
    ],
    'field_types' => [
        'text' => 'Text',
        'email' => 'Email',
        'phone' => 'Phone',
        'textarea' => 'Textarea',
        'select' => 'Select',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio',
        'file' => 'File Upload',
        'date' => 'Date',
        'number' => 'Number',
    ],
    'table' => [
        'title' => 'Title',
        'slug' => 'Slug',
        'submissions' => 'Submissions',
        'is_active' => 'Active',
        'created_at' => 'Created',
    ],
];

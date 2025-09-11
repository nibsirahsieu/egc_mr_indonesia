<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'main' => [
        'path' => './assets/main.js',
        'entrypoint' => true,
    ],
    'insights' => [
        'path' => './assets/js/insights.js',
        'entrypoint' => true,
    ],
    'whitepapers' => [
        'path' => './assets/js/whitepapers.js',
        'entrypoint' => true,
    ],
    'case_studies' => [
        'path' => './assets/js/case_studies.js',
        'entrypoint' => true,
    ],
    'admin' => [
        'path' => './assets/admin.js',
        'entrypoint' => true,
    ],
    'login' => [
        'path' => './assets/login.js',
        'entrypoint' => true,
    ],
    'post_form' => [
        'path' => './assets/js/post_form.js',
        'entrypoint' => true,
    ],
    'post_list' => [
        'path' => './assets/js/post_list.js',
        'entrypoint' => true,
    ],
    'inquiry_list' => [
        'path' => './assets/js/inquiry_list.js',
        'entrypoint' => true,
    ],
    'wp_request_list' => [
        'path' => './assets/js/wp_request_list.js',
        'entrypoint' => true,
    ],
    'case_study_list' => [
        'path' => './assets/js/case_study_list.js',
        'entrypoint' => true,
    ],
    'case_study_form' => [
        'path' => './assets/js/case_study_form.js',
        'entrypoint' => true,
    ],
    'url_redirection_list' => [
        'path' => './assets/js/url_redirection_list.js',
        'entrypoint' => true,
    ],
    'meta_section_list' => [
        'path' => './assets/js/meta_section_list.js',
        'entrypoint' => true,
    ],
    'header_footer_script' => [
        'path' => './assets/js/header_footer_script.js',
        'entrypoint' => true,
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    'feather-icons' => [
        'version' => '4.29.2',
    ],
    'perfect-scrollbar/dist/perfect-scrollbar.js' => [
        'version' => '1.5.6',
    ],
    'bootstrap' => [
        'version' => '5.3.8',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.8',
        'type' => 'css',
    ],
    'parsleyjs' => [
        'version' => '2.9.2',
    ],
    'lite-uploader' => [
        'version' => '6.0.0',
    ],
    'datatables.net-bs5' => [
        'version' => '2.3.3',
    ],
    'datatables.net' => [
        'version' => '2.3.3',
    ],
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css' => [
        'version' => '2.3.3',
        'type' => 'css',
    ],
    'datatables.net-fixedheader-bs5' => [
        'version' => '4.0.3',
    ],
    'datatables.net-fixedheader' => [
        'version' => '4.0.3',
    ],
    'datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css' => [
        'version' => '4.0.3',
        'type' => 'css',
    ],
    'datatables.net-responsive-bs5' => [
        'version' => '3.0.6',
    ],
    'datatables.net-responsive' => [
        'version' => '3.0.6',
    ],
    'datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css' => [
        'version' => '3.0.6',
        'type' => 'css',
    ],
    'autosize' => [
        'version' => '6.0.1',
    ],
    'bootstrap-maxlength' => [
        'version' => '2.0.0',
    ],
    '@toyokumo/fos-router' => [
        'version' => '1.0.5',
    ],
    'lazysizes' => [
        'version' => '5.3.2',
    ],
    'infinite-scroll' => [
        'version' => '5.0.0',
    ],
    'ev-emitter' => [
        'version' => '2.1.2',
    ],
    'fizzy-ui-utils' => [
        'version' => '3.0.0',
    ],
    'flyter' => [
        'version' => '0.3.6',
    ],
    'deepmerge' => [
        'version' => '4.3.1',
    ],
    '@erwinstone/bs-toast' => [
        'version' => '1.0.1',
    ],
    'alpinejs' => [
        'version' => '3.15.0',
    ],
    '@alpinejs/collapse' => [
        'version' => '3.15.0',
    ],
    '@alpinejs/focus' => [
        'version' => '3.15.0',
    ],
    'just-validate' => [
        'version' => '4.3.0',
    ],
    'lucide' => [
        'version' => '0.542.0',
    ],
];

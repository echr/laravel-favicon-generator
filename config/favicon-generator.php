<?php

return [

    /*
     * The disk on which to store generated icons.
     * Choose one of the disks you've configured in config/filesystems.php.
     */
    'disk_name' => 'public',

    /*
     * The path in which to store generated icons.
     */
    'icons_path' => 'icons',

    /*
     * The master picture which will be used for icons generating.
     *
     * Accepts:
     * - relative path to image (e.g. 'storage/design/logo.png');
     * - absolute path to image (e.g. '/var/www/example.com/storage/design/logo.png');
     * - image url (e.g. 'https://example.com/emoji/cat.png');
     * - base64 encoded image.
     */
    'master_picture' => null,

    /*
     * Favicon generation settings.
     *
     * @see https://realfavicongenerator.net/api/non_interactive_api
     */
    'favicon_generation' => [
        'favicon_design' => [
            'desktop_browser' => [],
            'ios' => [
                'picture_aspect' => 'background_and_margin',
                'margin' => '4',
                'background_color' => '#327500',
                'startup_image' => [
                    'background_color' => '#327500',
                ],
                'assets' => [
                    'ios6_and_prior_icons' => false,
                    'ios7_and_later_icons' => true,
                    'precomposed_icons' => false,
                    'declare_only_default_icon' => true,
                ],
            ],
            'windows' => [
                'picture_aspect' => 'white_silhouette',
                'background_color' => '#327500',
                'assets' => [
                    'windows_80_ie_10_tile' => true,
                    'windows_10_ie_11_edge_tiles' => [
                        'small' => false,
                        'medium' => true,
                        'big' => true,
                        'rectangle' => false,
                    ],
                ],
            ],
            'firefox_app' => [
                'picture_aspect' => 'circle',
                'keep_picture_in_circle' => 'true',
                'circle_inner_margin' => '5',
                'background_color' => '#456789',
                'manifest' => [
                    'app_name' => env('APP_NAME'),
                    // 'app_description' => 'Yet another sample application',
                    // 'developer_name' => 'Ilya Sakovich',
                    // 'developer_url' => 'https://hivokas.com',
                ],
            ],
            'android_chrome' => [
                'picture_aspect' => 'shadow',
                'manifest' => [
                    'name' => env('APP_NAME'),
                    'display' => 'standalone',
                    'orientation' => 'portrait',
                    'start_url' => '/',
                ],
                'assets' => [
                    'legacy_icon' => true,
                    'low_resolution_icons' => false,
                ],
                'theme_color' => '#4972ab',
            ],
            'safari_pinned_tab' => [
                'picture_aspect' => 'black_and_white',
                'threshold' => 60,
                'theme_color' => '#136497',
            ],
            'coast' => [
                'picture_aspect' => 'background_and_margin',
                'background_color' => '#136497',
                'margin' => '12%',
            ],
            'open_graph' => [
                'picture_aspect' => 'background_and_margin',
                'background_color' => '#136497',
                'margin' => '12%',
                'ratio' => '1.91:1',
            ],
            'yandex_browser' => [
                'background_color' => 'background_color',
                'manifest' => [
                    'show_title' => true,
                    'version' => '1.0',
                ],
            ],
        ],
        'settings' => [
            'compression' => '3',
            'scaling_algorithm' => 'Mitchell',
        ],
    ],

];

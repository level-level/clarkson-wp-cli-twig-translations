<?php

/*
Description: Smart WP-CLI command to cache the Twig templates so they can be read by PoEdit
Author: Level Level
Author URI: https://level-level.com
License: GPL 2.0
*/

namespace Clarkson\WPCLITwigTranslations;

use WP_CLI;

if( !defined('WP_CLI') || ! WP_CLI ) {
    return;
}

$autoload = __DIR__ . '/../../../vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

WP_CLI::add_command('clarkson-twig-translations', GetText::class, array(
    'synopsis' => array(
        array(
            'type'        => 'assoc',
            'name'        => 'template-dir',
            'description' => 'Directory where twig templates are found.',
            'optional'    => true,
            'default'     => '/templates/',
        ),
        array(
            'type'        => 'assoc',
            'name'        => 'output-dir',
            'description' => 'Directory where rendered PHP files are saved.',
            'optional'    => true,
            'default'     => '/dist/rendered-templates/',
        ),
    ),
    'when' => 'before_wp_load',
));

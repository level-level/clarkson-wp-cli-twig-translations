<?php

/*
Description: Smart WP-CLI command to cache the Twig templates so they can be read by PoEdit
Author: Level Level
Author URI: https://level-level.com
License: GPL 2.0
*/

namespace Clarkson\WPCLITwigTranslations;

use Twig\Extra\Html\HtmlExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\String\StringExtension;

if( !defined('WP_CLI') || ! WP_CLI ) {
    return;
}

$autoload = __DIR__ . '/../../../vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

if( ! class_exists( '\Clarkson\WPCLITwigTranslations\Gettext' ) ){
    class Gettext extends \WP_CLI_Command {
        public function __invoke( $args, $assoc_args ) {
            $twig_args = array(
                'debug' => true,
                'cache' => __DIR__ . '/../../../' . $assoc_args['output-dir'] ,
                'auto_reload' => true
            );

            $basedir = realpath( __DIR__ . '/../../../' . $assoc_args['template-dir'] );
            $twig_fs = new \Twig\Loader\FilesystemLoader( $basedir );
            $twig 	 = new \Twig\Environment( $twig_fs, $twig_args );
            $twig->registerUndefinedFunctionCallback(function ($name) {
                return new \Twig\TwigFunction($name, $name);
            });
            $twig->addExtension( new IntlExtension() );
            $twig->addExtension( new StringExtension() );
            $twig->addExtension( new HtmlExtension() );
            $twig->addExtension( new MarkdownExtension() );

            $filelist = $this->getFileList( $basedir );

            foreach($filelist as $filepath) {
                $truepath = str_replace( $basedir, '', $filepath );
                $twig->loadTemplate( $truepath );
            }
        }

        protected function getFileList( $dir ) {
            $filelist = array();
            $directories = glob( realpath( $dir ). '/*', GLOB_ONLYDIR );
            foreach( $directories as $directory ){
                $filelist = array_merge( $filelist, $this->getFileList( $directory ) );
            }
            $filelist = array_merge( $filelist, glob( realpath( $dir ) . '/*.twig' ) );
            return $filelist;
        }
    }

    \WP_CLI::add_command('clarkson-twig-translations', "\Clarkson\WPCLITwigTranslations\Gettext", array(
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
}

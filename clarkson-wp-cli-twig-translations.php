<?php

/*
Plugin Name: Clarkson WP-CLI Twig Translation
Plugin URI: https://github.com/level-level/clarkson-wp-cli-twig-translations
Description: Smart WP-CLI command to cache the Twig templates so they can be read by PoEdit, instead of buying PoEdit Pro :)
Version: 0.1.0
Author: Level Level
Author URI: https://level-level.com
*/

namespace Clarkson\WPCLITwigTranslations;

if(defined('WP_CLI') && WP_CLI) {
    class Gettext extends \WP_CLI_Command {

        /**
         * @alias prepare-files
         */
        public function prepareFiles(){
            $twig_args = array(
                'debug' => true,
                'cache' => apply_filters( 'clarkson_twig_translate_cache_path', realpath( get_stylesheet_directory() ) . '/dist/rendered-templates/' ),
                'auto_reload' => true
            );

            $basedir = apply_filters( 'clarkson_twig_translate_templates_path', realpath( get_stylesheet_directory() . '/templates/' ) );
            $twig_fs = new \Twig_Loader_Filesystem( $basedir );
            $twig 	 = new \Twig_Environment( $twig_fs, $twig_args );
            $twig->addExtension( new \Clarkson_Core_Twig_Extension()    );
            $twig->addExtension( new \Twig_Extensions_Extension_I18n()  );
            $twig->addExtension( new \Twig_Extensions_Extension_Text()  );
            $twig->addExtension( new \Twig_Extensions_Extension_Array() );
            $twig->addExtension( new \Twig_Extensions_Extension_Date()  );

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

    \WP_CLI::add_command('clarkson-twig-translations', "\Clarkson\WPCLITwigTranslations\Gettext");
}
<?php

namespace Clarkson\WPCLITwigTranslations;

use Twig\Extra\Html\HtmlExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\String\StringExtension;
use WP_CLI;
use WP_CLI_Command;

class GetText extends WP_CLI_Command {
    public function __invoke( $args, $assoc_args ) {
        $twig_args = array(
            'debug' => true,
            'cache' => __DIR__ . '/../../../../' . $assoc_args['output-dir'] ,
            'auto_reload' => true
        );

        $basedir = realpath( __DIR__ . '/../../../../' . $assoc_args['template-dir'] );
        $twig_fs = new \Twig\Loader\FilesystemLoader( $basedir );
        $twig 	 = new \Twig\Environment( $twig_fs, $twig_args );
        $twig->registerUndefinedFunctionCallback(function ($name) {
            return new \Twig\TwigFunction($name, $name);
        });
        $twig->addExtension( new IntlExtension() );
        $twig->addExtension( new StringExtension() );
        $twig->addExtension( new HtmlExtension() );
        $twig->addExtension( new MarkdownExtension() );
        $twig->addExtension( new Extension() );

        $filelist = $this->getFileList( $basedir );

        foreach($filelist as $filepath) {
            $truepath = str_replace( $basedir, '', $filepath );
            $twig->load( $truepath );
        }

        WP_CLI::success( 'Twig templates loaded' );
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
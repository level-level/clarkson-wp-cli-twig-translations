<?php

namespace Clarkson\WPCLITwigTranslations;

use Twig\Environment;
use Twig\Extra\Html\HtmlExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\String\StringExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use WP_CLI;
use WP_CLI_Command;

class GetText extends WP_CLI_Command {
    public function __invoke( $args, $assoc_args ) {

        if( !function_exists( '_x' ) ) {
            require_once( __DIR__ . '/../wordpress-stubs.php' );
        }

        $twig_args = array(
            'debug' => true,
            'cache' => __DIR__ . '/../../../../' . $assoc_args['output-dir'] ,
            'auto_reload' => true
        );

        $basedir = realpath( __DIR__ . '/../../../../' . $assoc_args['template-dir'] );
        
        $twig = $this->setup_twig( $basedir, $twig_args );

        $twig = $this->add_wildcards( $twig );

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

    /**
     * @param $twig_args array<string, mixed> Twig environment arguments
     */
    private function setup_twig( string $basedir, array $twig_args ): Environment{
        $twig_fs = new \Twig\Loader\FilesystemLoader( $basedir );
        $twig 	 = new \Twig\Environment( $twig_fs, $twig_args );
        $twig->addExtension( new IntlExtension() );
        $twig->addExtension( new StringExtension() );
        $twig->addExtension( new HtmlExtension() );
        $twig->addExtension( new MarkdownExtension() );
        return $twig;
    }

    /**
     * Add wildcard functions and filters so unrecognized functions and filters are available in translations
     * 
     * @see https://github.com/twigphp/Twig/blob/v3.12.0/CHANGELOG
     */
    private function add_wildcards( Environment $twig ): Environment{
        $wildcard_function = new TwigFunction( '*', function ( $args ) {
            return $args;
        } );
        $twig->addFunction( $wildcard_function );

        $wildcard_filter = new TwigFilter( '*', function ( $args ) {
            return $args;
        } );
        $twig->addFilter( $wildcard_filter );
        return $twig;
    }
}

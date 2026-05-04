<?php

namespace NoTOCModern;

use Parser;
use ParserOutput;
use OutputPage;
use Skin;

class Hooks {

    /**
     * Detecta __SHOWTOC__
     */
    public static function onParserBeforeInternalParse(
        Parser $parser,
        &$text,
        &$stripState
    ) {
        if ( strpos( $text, '__SHOWTOC__' ) === false ) {
            return true;
        }

        $text = str_replace( '__SHOWTOC__', '', $text );

        $output = $parser->getOutput();

        if ( $output instanceof ParserOutput ) {
            $output->setPageProperty( 'showtoc', '1' );
        }

        return true;
    }

    /**
     * Marca páginas especiales como sin TOC
     */
    public static function onOutputPageParserOutput(
        OutputPage $out,
        ParserOutput $parserOutput
    ) {
        $title = $out->getTitle();

        if ( $title && $title->isSpecialPage() ) {
            $out->setProperty( 'notoc', true );
            return;
        }

        if ( !$parserOutput->getPageProperty( 'showtoc' ) ) {
            $out->setProperty( 'notoc', true );
        }
    }

    /**
     * Aplicación final (solo CSS)
     */
    public static function onBeforePageDisplay(
        OutputPage $out,
        Skin $skin
    ) {
        if ( $out->getProperty( 'notoc' ) ) {
            $out->addInlineStyle(
                '#toc, .toc, .mw-table-of-contents { display:none !important; }'
            );
        }
    }
}
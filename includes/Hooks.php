<?php

use Parser;
use OutputPage;
use Skin;

class NoTOCModernHooks {

    /**
     * Hides the table of contents by default on normal wiki pages.
     *
     * If the page contains __TOC__ or __FORCETOC__, MediaWiki's native
     * behavior is preserved and the TOC is displayed normally.
     *
     * @param Parser $parser
     * @param string &$text
     * @param mixed $stripState
     * @return bool
     */
    public static function onParserBeforeInternalParse( Parser $parser, &$text, $stripState ) {
        /*
         * Backward compatibility:
         * If older versions of this extension used __SHOWTOC__,
         * remove it from visible output.
         */
        $text = preg_replace( '/__SHOWTOC__/i', '', $text );

        /*
         * If the page explicitly requests a TOC, do nothing.
         *
         * __TOC__ displays the table of contents at the marker position.
         * __FORCETOC__ forces the table of contents in the default position.
         */
        if (
            preg_match( '/__TOC__/i', $text ) ||
            preg_match( '/__FORCETOC__/i', $text )
        ) {
            return true;
        }

        /*
         * If the page already has __NOTOC__, do nothing.
         */
        if ( preg_match( '/__NOTOC__/i', $text ) ) {
            return true;
        }

        /*
         * Otherwise, hide the TOC by default using MediaWiki's native
         * __NOTOC__ behavior switch.
         */
        $text = "__NOTOC__\n" . $text;

        return true;
    }

    /**
     * Optionally hides the table of contents on special pages.
     *
     * Special pages usually do not have editable wikitext, so __TOC__
     * cannot normally be used there. This behavior is controlled through
     * $wgNoTOCModernHideSpecialPages.
     *
     * @param OutputPage $out
     * @param Skin $skin
     * @return bool
     */
    public static function onBeforePageDisplay( OutputPage $out, Skin $skin ) {
        global $wgNoTOCModernHideSpecialPages;

        $title = $out->getTitle();

        if ( !$title ) {
            return true;
        }

        if (
            defined( 'NS_SPECIAL' ) &&
            $title->getNamespace() === NS_SPECIAL &&
            !empty( $wgNoTOCModernHideSpecialPages )
        ) {
            self::addHideTocCss( $out );
        }

        return true;
    }

    /**
     * Adds inline CSS to hide TOC elements on special pages.
     *
     * @param OutputPage $out
     * @return void
     */
    private static function addHideTocCss( OutputPage $out ) {
        $css = '
            .mw-parser-output .toc,
            .mw-parser-output #toc,
            .toc,
            #toc,
            .vector-toc,
            .vector-page-titlebar-toc {
                display: none !important;
            }
        ';

        $out->addInlineStyle( $css );
    }
}
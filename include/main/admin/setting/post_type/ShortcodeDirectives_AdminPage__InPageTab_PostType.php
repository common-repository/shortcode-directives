<?php
/**
 * Shortcode Directives
 *
 *
 * http://en.michaeluno.jp/shortcode-directivies
 * Copyright (c) 2020 Michael Uno; Licensed under <LICENSE_TYPE>
 *
 */

/**
 * Adds the 'Items' tab to the 'Settings' page of the loader plugin.
 *
 * @since    0.0.3
 * @extends  ShortcodeDirectives_AdminPage__InPageTab_Base
 */
class ShortcodeDirectives_AdminPage__InPageTab_PostType extends ShortcodeDirectives_AdminPage__InPageTab_Base {

    /**
     * @return      array
     */
    protected function _getArguments( $oFactory ) {
        return array(
            'tab_slug'  => 'post_types',
            'title'     => __( 'Post Types', 'shortcode-directives' ),
        );
    }
    
    /**
     * Triggered when the tab is loaded.
     */
    protected function _load( $oFactory ) {

        // Form sections
        new ShortcodeDirectives_AdminPage__FormSection_PostType( $oFactory, $this->_sPageSlug, $this->_sTabSlug );

    }


    /**
     * @param $oFactory
     */
    protected function _do( $oFactory ) {
        echo "<div class='right-submit-button'>"
                . get_submit_button()
            . "</div>";
    }

}

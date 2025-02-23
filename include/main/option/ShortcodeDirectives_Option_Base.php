<?php
/**
 * Shortcode Directives
 * 
 * http://en.michaeluno.jp/shortcode-directivies
 * Copyright (c) 2020 Michael Uno
 * 
 */

/**
 * Provides common methods for option objects.
 * 
 * @since    0.0.1
 */
class ShortcodeDirectives_Option_Base extends ShortcodeDirectives_PluginUtility {
    
    /**
     * Stores the option values.
     * 
     * @access      public      Let the data being modified from outside.
     */
    public $aOptions = array(        
    );  

    /**
     * stores the option key for this plugin. 
     */
    protected $sOptionKey = '';        
         
    /**
     * Stores whether the currently loading page is in the network admin area.
     */
    protected $bIsNetworkAdmin = false;     
         
    /**
     * Sets up properties.
     */
    public function __construct( $sOptionKey ) {
        
        $this->bIsNetworkAdmin  = false; // disabled.
        $this->sOptionKey       = $sOptionKey;
        $this->aOptions         = $this->_getFormatted( $sOptionKey );

    }     
        /**
         * Returns the formatted options array.
         * @remark  Override this method in an extended class.
         * @return  array
         */    
        protected function _getFormatted( $sOptionKey ) {
            return $this->uniteArrays(
                $this->getAsArray(
                    $this->bIsNetworkAdmin
                        ? get_site_option( $sOptionKey, array() )
                        : get_option( $sOptionKey, array() )
                ),
                $this->getDefaults()
            );
        }
    
    /**
     * Returns the default option values.
     * @since       0.a.1
     * @return      array
     */
    public function getDefaults() {
        return apply_filters(
            ShortcodeDirectives_Registry::HOOK_SLUG . '_filter_default_options',
            ShortcodeDirectives_Registry::$aOptions
        );
    }    
    
    /**
     * Checks the version number
     * 
     * @since    0.0.1
     * @return      boolean        True if yes; otherwise, false.
     * @remrk       not used at the moment
     */
    public function hasUpgraded() {
        
        $_sOptionVersion        = $this->get( 'version_saved' );
        if ( ! $_sOptionVersion ) {
            return false;
        }
        $_sOptionVersion        = $this->_getVersionByDepth( $_sOptionVersion );
        $_sCurrentVersion       = $this->_getVersionByDepth( ShortcodeDirectives_Registry::VERSION );
        return version_compare( $_sOptionVersion, $_sCurrentVersion, '<' );
        
    }
        /**
         * Returns a stating part of version by the given depth.
         * @since    0.0.1
         */
        private function _getVersionByDepth( $sVersion, $iDepth=2 ) {
            if ( ! $iDepth ) {
                return $sVersion;
            }
            $_aParts = explode( '.', $sVersion );
            $_aParts = array_slice( $_aParts, 0, $iDepth );
            return implode( '.', $_aParts );
        }    
    
    /**
     * Deletes the option from the database.
     */
    public function delete()  {
        return $this->bIsNetworkAdmin
            ? delete_site_option( $this->sOptionKey )
            : delete_option( $this->sOptionKey );
    }
    
    /**
     * Saves the options.
     */
    public function save( $aOptions=null ) {

        $_aOptions = $aOptions 
            ? $aOptions 
            : $this->aOptions;
        return $this->bIsNetworkAdmin
            ? update_site_option(
                $this->sOptionKey, 
                $_aOptions
            )
            : update_option( 
                $this->sOptionKey, 
                $_aOptions
            );
    }
    
    /**
     * Sets the options.
     */
    public function set( /* $asKeys, $mValue */ ) {
        
        $_aParameters   = func_get_args();
        if ( ! isset( $_aParameters[ 0 ], $_aParameters[ 1 ] ) ) {
            return;
        }
        $_asKeys        = $_aParameters[ 0 ];
        $_mValue        = $_aParameters[ 1 ];
        
        // string, integer, float, boolean
        if ( ! is_array( $_asKeys ) ) {
            $this->aOptions[ $_asKeys ] = $_mValue;
            return;
        }
        
        // the keys are passed as an array
        $this->setMultiDimensionalArray( 
            $this->aOptions, 
            $_asKeys,
            $_mValue 
        );

    }
    
    /**
     * Sets and save the options.
     */
    public function update( /* $asKeys, $mValue */ ) {
        
        $_aParameters = func_get_args();
        call_user_func_array( array( $this, 'set' ), $_aParameters );
        $this->save();

    }

    /**
     * Returns the specified option value.
     * 
     * @since    0.0.1
     */
    public function get( /* $sKey1, $sKey2, $sKey3, ... OR $aKeys, $vDefault */ ) {
        
        $_mDefault     = null;
        $_aKeys        = func_get_args() + array( null, null );
        if ( ! isset( $_aKeys[ 0 ] ) ) {
            return $this->aOptions;
        }
        if ( is_array( $_aKeys[ 0 ] ) ) {
            $_mDefault = $_aKeys[ 1 ];
            $_aKeys    = $_aKeys[ 0 ];
        }
        return $this->getArrayValueByArrayKeys( 
            $this->aOptions, 
            $_aKeys,
            $_mDefault
        );
        
    }
  
    
}
<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shortcode-directives>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class ShortcodeDirectives_AdminPageFramework_NetworkAdmin extends ShortcodeDirectives_AdminPageFramework {
    protected $_sStructureType = 'network_admin_page';
    protected $_aBuiltInRootMenuSlugs = array('dashboard' => 'index.php', 'sites' => 'sites.php', 'themes' => 'themes.php', 'plugins' => 'plugins.php', 'users' => 'users.php', 'settings' => 'settings.php', 'updates' => 'update-core.php',);
    public function __construct($sOptionKey = null, $sCallerPath = null, $sCapability = 'manage_network', $sTextDomain = 'shortcode-directives') {
        if (!$this->_isInstantiatable()) {
            return;
        }
        $sCallerPath = $sCallerPath ? $sCallerPath : ShortcodeDirectives_AdminPageFramework_Utility::getCallerScriptPath(__FILE__);
        parent::__construct($sOptionKey, $sCallerPath, $sCapability, $sTextDomain);
        new ShortcodeDirectives_AdminPageFramework_Model_Menu__RegisterMenu($this, 'network_admin_menu');
    }
    protected function _getLinkObject() {
        $_sClassName = $this->aSubClassNames['oLink'];
        return new $_sClassName($this->oProp, $this->oMsg);
    }
    protected function _getPageLoadObject() {
        $_sClassName = $this->aSubClassNames['oPageLoadInfo'];
        return new $_sClassName($this->oProp, $this->oMsg);
    }
    protected function _isInstantiatable() {
        if (is_network_admin()) {
            return true;
        }
        return false;
    }
    static public function getOption($sOptionKey, $asKey = null, $vDefault = null) {
        return ShortcodeDirectives_AdminPageFramework_WPUtility::getSiteOption($sOptionKey, $asKey, $vDefault);
    }
}

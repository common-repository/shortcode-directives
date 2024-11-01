<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shortcode-directives>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class ShortcodeDirectives_AdminPageFramework_Form_Model___Modifier_Base extends ShortcodeDirectives_AdminPageFramework_FrameworkUtility {
}
class ShortcodeDirectives_AdminPageFramework_Form_Model___Modifier_FilterRepeatableElements extends ShortcodeDirectives_AdminPageFramework_Form_Model___Modifier_Base {
    public $aSubject = array();
    public $aDimensionalKeys = array();
    public function __construct() {
        $_aParameters = func_get_args() + array($this->aSubject, $this->aDimensionalKeys,);
        $this->aSubject = $_aParameters[0];
        $this->aDimensionalKeys = array_unique($_aParameters[1]);
    }
    public function get() {
        foreach ($this->aDimensionalKeys as $_sFlatFieldAddress) {
            $this->unsetDimensionalArrayElement($this->aSubject, explode('|', $_sFlatFieldAddress));
        }
        return $this->aSubject;
    }
}

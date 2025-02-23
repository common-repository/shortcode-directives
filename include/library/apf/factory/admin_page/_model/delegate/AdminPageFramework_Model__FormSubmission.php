<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shortcode-directives>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class ShortcodeDirectives_AdminPageFramework_Model__FormSubmission_Base extends ShortcodeDirectives_AdminPageFramework_Form_Utility {
    protected function _getPressedSubmitButtonData(array $aPostElements, $sTargetKey = 'field_id') {
        foreach ($aPostElements as $_sInputID => $_aSubElements) {
            if (!isset($_aSubElements['name'])) {
                continue;
            }
            $_aNameKeys = explode('|', $_aSubElements['name']);
            if (null === $this->getElement($_POST, $_aNameKeys, null)) {
                continue;
            }
            return $this->getElement($_aSubElements, $sTargetKey, null);
        }
        return null;
    }
    protected function _setSettingNoticeAfterValidation($bIsInputEmtpy) {
        if ($this->oFactory->hasSettingNotice()) {
            return;
        }
        $this->oFactory->setSettingNotice($this->getAOrB($bIsInputEmtpy, $this->oFactory->oMsg->get('option_cleared'), $this->oFactory->oMsg->get('option_updated')), 'updated', $this->oFactory->oProp->sOptionKey, false);
    }
}
class ShortcodeDirectives_AdminPageFramework_Model__FormSubmission extends ShortcodeDirectives_AdminPageFramework_Model__FormSubmission_Base {
    public $oFactory;
    public function __construct($oFactory, $aSavedData, $aArguments, $aSectionsets, $aFieldsets) {
        $this->oFactory = $oFactory;
        $this->_handleFormData();
        new ShortcodeDirectives_AdminPageFramework_Model__FormRedirectHandler($oFactory);
    }
    public function _handleFormData() {
        if (!$this->_shouldProceed()) {
            return;
        }
        $_sTabSlug = $this->getElement($_POST, 'tab_slug', '');
        $_sPageSlug = $this->getElement($_POST, 'page_slug', '');
        $_aDefaultOptions = $this->oFactory->oForm->getDefaultFormValues();
        $_aOptions = $this->addAndApplyFilter($this->oFactory, "validation_saved_options_{$this->oFactory->oProp->sClassName}", $this->uniteArrays($this->oFactory->oProp->aOptions, $_aDefaultOptions), $this->oFactory);
        $_aRawInputs = $this->_getUserInputsFromPOST();
        $_aInputs = $this->uniteArrays($_aRawInputs, $this->castArrayContents($_aRawInputs, $this->_removePageElements($_aDefaultOptions, $_sPageSlug, $_sTabSlug)));
        $_aSubmits = $this->getElementAsArray($_POST, '__submit', array());
        $_sSubmitSectionID = $this->_getPressedSubmitButtonData($_aSubmits, 'section_id');
        $_sPressedFieldID = $this->_getPressedSubmitButtonData($_aSubmits, 'field_id');
        $_sPressedInputID = $this->_getPressedSubmitButtonData($_aSubmits, 'input_id');
        $this->_doActions_submit($_aInputs, $_aOptions, $_sPageSlug, $_sTabSlug, $_sSubmitSectionID, $_sPressedFieldID, $_sPressedInputID);
        new ShortcodeDirectives_AdminPageFramework_Model__FormSubmission__Validator($this->oFactory);
        $_aInputs = $this->addAndApplyFilters($this->oFactory, "validation_pre_{$this->oFactory->oProp->sClassName}", $_aInputs, $_aRawInputs, $_aOptions, $this->oFactory);
        $_bUpdated = false;
        if (!$this->oFactory->oProp->_bDisableSavingOptions) {
            $_bUpdated = $this->oFactory->oProp->updateOption($_aInputs);
        }
        $this->_doActions_submit_after($_aInputs, $_aOptions, $_sPageSlug, $_sTabSlug, $_sSubmitSectionID, $_sPressedFieldID, $_bUpdated);
        $this->goToLocalURL($this->_getSettingUpdateURL(array('settings-updated' => true), $_sPageSlug, $_sTabSlug));
    }
    private function _shouldProceed() {
        if (!isset($_POST['admin_page_framework_start'], $_POST['_wp_http_referer'])) {
            return false;
        }
        $_sRequestURI = remove_query_arg(array('settings-updated', 'confirmation', 'field_errors'), wp_unslash($_SERVER['REQUEST_URI']));
        $_sReffererURI = remove_query_arg(array('settings-updated', 'confirmation', 'field_errors'), $_POST['_wp_http_referer']);
        if ($_sRequestURI != $_sReffererURI) {
            return false;
        }
        if (!isset($_POST['_is_admin_page_framework'], $_POST['page_slug'], $_POST['tab_slug'])) {
            $this->oFactory->setAdminNotice(sprintf($this->oFactory->oMsg->get('check_max_input_vars'), function_exists('ini_get') ? ini_get('max_input_vars') : 'unknown', count($_POST, COUNT_RECURSIVE)));
            return false;
        }
        $_sNonceTransientKey = 'form_' . md5($this->oFactory->oProp->sClassName . get_current_user_id());
        if ($_POST['_is_admin_page_framework'] !== $this->getTransient($_sNonceTransientKey)) {
            $this->oFactory->setAdminNotice($this->oFactory->oMsg->get('nonce_verification_failed'));
            return false;
        }
        return true;
    }
    private function _getUserInputsFromPOST() {
        $_aInputs = $this->getElementAsArray($_POST, $this->oFactory->oProp->sOptionKey, array());
        return $this->oFactory->oForm->getSubmittedData($_aInputs, false);
    }
    private function _doActions_submit($_aInputs, $_aOptions, $_sPageSlug, $_sTabSlug, $_sSubmitSectionID, $_sPressedFieldID, $_sPressedInputID) {
        if (has_action("submit_{$this->oFactory->oProp->sClassName}_{$_sPressedInputID}")) {
            $this->oFactory->oUtil->showDeprecationNotice('The hook, submit_{instantiated class name}_{pressed input id},', 'submit_{instantiated class name}_{pressed field id}');
        }
        $this->addAndDoActions($this->oFactory, array("submit_{$this->oFactory->oProp->sClassName}_{$_sPressedInputID}", $_sSubmitSectionID ? "submit_{$this->oFactory->oProp->sClassName}_{$_sSubmitSectionID}_{$_sPressedFieldID}" : "submit_{$this->oFactory->oProp->sClassName}_{$_sPressedFieldID}", $_sSubmitSectionID ? "submit_{$this->oFactory->oProp->sClassName}_{$_sSubmitSectionID}" : null, isset($_POST['tab_slug']) ? "submit_{$this->oFactory->oProp->sClassName}_{$_sPageSlug}_{$_sTabSlug}" : null, "submit_{$this->oFactory->oProp->sClassName}_{$_sPageSlug}", "submit_{$this->oFactory->oProp->sClassName}",), $_aInputs, $_aOptions, $this->oFactory);
    }
    private function _doActions_submit_after($_aInputs, $_aOptions, $_sPageSlug, $_sTabSlug, $_sSubmitSectionID, $_sPressedFieldID, $_bUpdated) {
        $this->addAndDoActions($this->oFactory, array($this->getAOrB($_sSubmitSectionID, "submit_after_{$this->oFactory->oProp->sClassName}_{$_sSubmitSectionID}_{$_sPressedFieldID}", "submit_after_{$this->oFactory->oProp->sClassName}_{$_sPressedFieldID}"), $this->getAOrB($_sSubmitSectionID, "submit_after_{$this->oFactory->oProp->sClassName}_{$_sSubmitSectionID}", null), $this->getAOrB(isset($_POST['tab_slug']), "submit_after_{$this->oFactory->oProp->sClassName}_{$_sPageSlug}_{$_sTabSlug}", null), "submit_after_{$this->oFactory->oProp->sClassName}_{$_sPageSlug}", "submit_after_{$this->oFactory->oProp->sClassName}",), $_bUpdated ? $_aInputs : array(), $_aOptions, $this->oFactory);
    }
    private function _getSettingUpdateURL(array $aStatus, $sPageSlug, $sTabSlug) {
        $aStatus = $this->addAndApplyFilters($this->oFactory, array("options_update_status_{$sPageSlug}_{$sTabSlug}", "options_update_status_{$sPageSlug}", "options_update_status_{$this->oFactory->oProp->sClassName}",), $aStatus);
        $_aRemoveQueries = array();
        if (!isset($aStatus['field_errors']) || !$aStatus['field_errors']) {
            unset($aStatus['field_errors']);
            $_aRemoveQueries[] = 'field_errors';
        }
        return $this->addAndApplyFilters($this->oFactory, array("setting_update_url_{$this->oFactory->oProp->sClassName}",), $this->getQueryURL($aStatus, $_aRemoveQueries, $_SERVER['REQUEST_URI']));
    }
    private function _removePageElements($aOptions, $sPageSlug, $sTabSlug) {
        if (!$sPageSlug && !$sTabSlug) {
            return $aOptions;
        }
        if ($sTabSlug && $sPageSlug) {
            return $this->oFactory->oForm->getOtherTabOptions($aOptions, $sPageSlug, $sTabSlug);
        }
        return $this->oFactory->oForm->getOtherPageOptions($aOptions, $sPageSlug);
    }
}

<?php 
/**
	Admin Page Framework v3.8.18 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/shortcode-directives>
	Copyright (c) 2013-2018, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class ShortcodeDirectives_AdminPageFramework_PageLoadInfo_Base extends ShortcodeDirectives_AdminPageFramework_FrameworkUtility {
    public $oProp;
    public $oMsg;
    protected $_nInitialMemoryUsage;
    public function __construct($oProp, $oMsg) {
        if (!$this->_shouldProceed($oProp)) {
            return;
        }
        $this->oProp = $oProp;
        $this->oMsg = $oMsg;
        $this->_nInitialMemoryUsage = memory_get_usage();
        add_action('in_admin_footer', array($this, '_replyToSetPageLoadInfoInFooter'), 999);
    }
    private function _shouldProceed($oProp) {
        if ($oProp->bIsAdminAjax || !$oProp->bIsAdmin) {
            return false;
        }
        return ( boolean )$oProp->bShowDebugInfo;
    }
    public function _replyToSetPageLoadInfoInFooter() {
    }
    static private $_bLoadedPageLoadInfo = false;
    public function _replyToGetPageLoadInfo($sFooterHTML) {
        if (!$this->oProp->bShowDebugInfo) {
            return $sFooterHTML;
        }
        if (self::$_bLoadedPageLoadInfo) {
            return $sFooterHTML;
        }
        self::$_bLoadedPageLoadInfo = true;
        return $sFooterHTML . $this->_getPageLoadStats();
    }
    private function _getPageLoadStats() {
        $_nSeconds = timer_stop(0);
        $_nQueryCount = get_num_queries();
        $_nMemoryUsage = round($this->_convertBytesToHR(memory_get_usage()), 2);
        $_nMemoryPeakUsage = round($this->_convertBytesToHR(memory_get_peak_usage()), 2);
        $_nMemoryLimit = round($this->_convertBytesToHR($this->_convertToNumber(WP_MEMORY_LIMIT)), 2);
        $_sInitialMemoryUsage = round($this->_convertBytesToHR($this->_nInitialMemoryUsage), 2);
        return "<div id='shortcode-directives-page-load-stats'>" . "<ul>" . "<li>" . sprintf($this->oMsg->get('queries_in_seconds'), $_nQueryCount, $_nSeconds) . "</li>" . "<li>" . sprintf($this->oMsg->get('out_of_x_memory_used'), $_nMemoryUsage, $_nMemoryLimit, round(($_nMemoryUsage / $_nMemoryLimit), 2) * 100 . '%') . "</li>" . "<li>" . sprintf($this->oMsg->get('peak_memory_usage'), $_nMemoryPeakUsage) . "</li>" . "<li>" . sprintf($this->oMsg->get('initial_memory_usage'), $_sInitialMemoryUsage) . "</li>" . "</ul>" . "</div>";
    }
    private function _convertToNumber($nSize) {
        $_nReturn = substr($nSize, 0, -1);
        switch (strtoupper(substr($nSize, -1))) {
            case 'P':
                $_nReturn*= 1024;
            case 'T':
                $_nReturn*= 1024;
            case 'G':
                $_nReturn*= 1024;
            case 'M':
                $_nReturn*= 1024;
            case 'K':
                $_nReturn*= 1024;
        }
        return $_nReturn;
    }
    private function _convertBytesToHR($nBytes) {
        $_aUnits = array(0 => 'B', 1 => 'kB', 2 => 'MB', 3 => 'GB');
        $_nLog = log($nBytes, 1024);
        $_iPower = ( int )$_nLog;
        $_iSize = pow(1024, $_nLog - $_iPower);
        return $_iSize . $_aUnits[$_iPower];
    }
}

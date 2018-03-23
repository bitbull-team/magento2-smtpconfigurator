<?php
/**
 * @copyright Copyright © 2018 FitForMe. All rights reserved.
 * @author    n.sala@fitforme.nl
 */
namespace Ffm\SmtpConfigurator\Helper;

/**
 * Ffm SmtpConfigurator helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SMTP_ENABLED = 'smtp/general/enabled';
    const XML_PATH_SMTP_AUTHENTICATION = 'smtp/general/authentication';
    const XML_PATH_SMTP_HOST = 'smtp/general/host';
    const XML_PATH_SMTP_PORT = 'smtp/general/port';
    const XML_PATH_SMTP_USERNAME = 'smtp/general/username';
    const XML_PATH_SMTP_PASSWORD = 'smtp/general/password';
    const XML_PATH_SMTP_PROOCOL = 'smtp/general/protocol';

    const REGISTRY_KEY_MESSAGE = 'ffm_smtpconfigurator_smtp_message';
    const REGISTRY_KEY_TESTMODE = 'ffm_smtpconfigurator_smtp_testmode';

    /**
     * @return bool
     */
    public function getConfigSmtpEnabled():bool
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->isSetFlag(self::XML_PATH_SMTP_ENABLED, $storeScope);
    }

    /**
     * @return string
     */
    public function getConfigSmtpAuthentication():string
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SMTP_AUTHENTICATION, $storeScope);
    }

    /**
     * @return string
     */
    public function getConfigSmtpHost():string
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SMTP_HOST, $storeScope);
    }

    /**
     * @return string
     */
    public function getConfigSmtpPort():string
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SMTP_PORT, $storeScope);
    }

    /**
     * @return string
     */
    public function getConfigSmtpUsername():string
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SMTP_USERNAME, $storeScope);
    }

    /**
     * @return string
     */
    public function getConfigSmtpPassword():string
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SMTP_PASSWORD, $storeScope);
    }

    /**
     * @return string
     */
    public function getConfigSmtpProtocol():string
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return (string)$this->scopeConfig->getValue(self::XML_PATH_SMTP_PROOCOL, $storeScope);
    }
}
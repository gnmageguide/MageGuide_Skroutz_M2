<?php

namespace MageGuide\Skroutz\Helper;

/**
 * Helper class
 * @package  MageGuide_Skroutz
 * @module   Skroutz
 * @author   MageGuide Developer
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	/**
     * @var array
     */
    protected $_skroutzOptions;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_skroutzOptions = $this->scopeConfig->getValue('mageguide_skroutz', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->_skroutzOptions['general']['status'];
    }

    /**
     * @return string
     */
    public function getProgramID()
    {
        return trim($this->_skroutzOptions['general']['program_id']);
    }

}

?>
<?php

namespace MageGuide\Skroutz\Block;

/**
 * Block class for order success page
 * @package  MageGuide_Skroutz
 * @module   Skroutz
 * @author   MageGuide Developer
 */
class SkroutzSuccess extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
	protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable
     */
    protected $_catalogProductTypeConfigurable;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * constructor class
     *
     * @param \Magento\Checkout\Model\Session                                               $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory                                             $orderFactory
     * @param \Magento\Catalog\Model\Product                                                $product
     * @param \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable    $catalogProductTypeConfigurable
     * @param \Magento\Framework\View\Element\Template\Context                              $context
     */
	public function __construct(
		\Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Catalog\Model\Product $product,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $catalogProductTypeConfigurable,
        \Magento\Framework\View\Element\Template\Context $context)
	{
		$this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        $this->_product = $product;
        $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->_scopeConfig = $context->getScopeConfig();
        
        if ($this->_checkoutSession->getLastRealOrderId()) {
        	$this->_order = $this->_orderFactory->create()->loadByIncrementId($this->_checkoutSession->getLastRealOrderId());
        }
        parent::__construct($context);
	}

    /**
     * Returns the id of the last order
     *
     * @return integer|boolean
     */
    public function getRealOrderId()
    {
    	$order = $this->_order;
    	if ($order) {
            $lastorderId = $order->getId();
            return $lastorderId;
        }
        return false;
    }

    /**
     * Returns the order subtotal with added tax and shipping fee
     *
     * @return string|boolean
     */
    public function getPrice()
    {
        $order = $this->_order;
        if ($order) {
            $price = number_format($order->getSubtotalInclTax() + $order->getShippingInclTax(), 2);
            return $price;
        }
        return false;
    }

    /**
     * Returns the order shipping fee
     *
     * @return string|boolean
     */
    public function getShippingCost()
    {
        $order = $this->_order;
        if ($order) {
            $shippingCost = number_format($order->getShippingInclTax(), 2);
            return $shippingCost;
        }
        return false;
    }

    /**
     * Returns the order tax amount
     *
     * @return string|boolean
     */
    public function getTaxAmount()
    {
        $order = $this->_order;
        if ($order) {
            $revenuefortax = $order->getSubtotalInclTax() + $order->getShippingInclTax();
            $taxtotal = $revenuefortax / 1.24;
			$taxAmountAlmost = $revenuefortax - $taxtotal;
			$taxAmount = number_format($taxAmountAlmost, 2);
            return $taxAmount;
        }
        return false;
    }

    /**
     * Returns all order items
     *
     * @return array|boolean
     */
    public function getAllOrderVisibleItems()
    {
        $order = $this->_order;
        if ($order) {
        	$items = $order->getAllVisibleItems();
            return $items;
        }
        return false;
    }

    /**
     * Returns the id of a product given the sku
     *
     * @return integer|boolean
     */
    public function getChildId($sku)
    {
        return $this->_product->getIdBySku($sku);
    }

    /**
     * Returns the id of a parent configurable product given the id of a child simple product
     *
     * @return integer|boolean
     */
    public function getParentId($childId)
    {
    	$parentByChild = $this->_catalogProductTypeConfigurable->getParentIdsByChild($childId);
		if(isset($parentByChild[0])){
            $parentId = $parentByChild[0];
            return $parentId;      
        }
        return false;
	}

    /**
     * Returns the sku of a product given the id
     *
     * @return string|boolean
     */
	public function getSkuFromId($productId)
    {
        return $this->_product->load($productId)->getSku();
    }

}
<?php

namespace AllezTech\SalesNotification\Block;


class SalesNotification extends \Magento\Framework\View\Element\Template {
    
    protected $context;
    protected $_registry;
    protected $_productloader;
    protected $_salesNotificationData;



    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Registry $_registry,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\App\Config\ScopeConfigInterface $_scopeConfig,
        \AllezTech\SalesNotification\Helper\Data $_salesNotificationData,
        array $data = []) 
    {
        $this->_registry = $_registry;
        $this->_productloader = $_productloader;
        $this->_scopeConfig = $_scopeConfig;
        $this->_salesNotificationData = $_salesNotificationData;
        parent::__construct($context, $data);
    }

    public function getHowLong(){
        $howlong =  $this->_scopeConfig->getValue(
            'salesnotification/general/howlong', 
              \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return $howlong*1000+5000;
    }

    public function getPosition(){
        return  $this->_scopeConfig->getValue(
            'salesnotification/general/position_select', 
              \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

    }

    public function getStatus(){
        return  $this->_scopeConfig->getValue(
            'salesnotification/general/enable', 
              \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

    }

    public function getLastOrderID(){
        return  $this->_salesNotificationData->getRealOrderId();
    }


    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }


    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    
}
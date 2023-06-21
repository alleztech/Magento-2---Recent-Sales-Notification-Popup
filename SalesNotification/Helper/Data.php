<?php
namespace AllezTech\SalesNotification\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;


class Data extends AbstractHelper
{
    protected $_checkoutSession;
    protected $_orderFactory;
    protected $_scopeConfig;
    protected $_orderCollectionFactory;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $_orderFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $_orderCollectionFactory,
        \Magento\Framework\View\Element\Context $context
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $_orderFactory;
        $this->_orderCollectionFactory = $_orderCollectionFactory;
        $this->_scopeConfig = $context->getScopeConfig();
    }

    public function getRealOrderId()
    {
        $collection = $this->_orderCollectionFactory->create()
          ->addAttributeToSelect('*')
          ->addAttributeToSort('entity_id', 'desc')
          ->setPageSize(1);
          ;
 

         foreach($collection as $item){
            $result = $item->getId();
         }

         return $result;
     }



     // Use this method to get ID    
    
 
     public function getOrder()
     {
         if ($this->getRealOrderId()) {
              $order = $this->_orderFactory->create()->load($this->getRealOrderId());
         return $order;
         }
         return false;
     }
 

}
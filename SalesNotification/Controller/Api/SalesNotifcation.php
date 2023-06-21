<?php

namespace AllezTech\SalesNotification\Controller\Api;
use Magento\Framework\App\Action\HttpPostActionInterface;

class SalesNotifcation extends \Magento\Framework\App\Action\Action 
//implements HttpPostActionInterface
{

    protected $imageHelper;
    protected $request;
    protected $_pageFactory;
    protected $resultJsonFactory;
    protected $_scopeConfig;
    protected $_productloader;
    protected $_productCollectionFactory;
    protected $_salesNotificationData;

    public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $_pageFactory,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\App\Config\ScopeConfigInterface $_scopeConfig,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $_productCollectionFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Helper\Image $imageHelper, 
        \AllezTech\SalesNotification\Helper\Data $_salesNotificationData,
		)
	{
		$this->_pageFactory = $_pageFactory;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->_scopeConfig = $_scopeConfig;
        $this->_productloader = $_productloader;
        $this->_productCollectionFactory = $_productCollectionFactory;
        $this->request = $request;
        $this->imageHelper = $imageHelper;
        $this->_salesNotificationData = $_salesNotificationData;
		return parent::__construct($context);
	}

    public function getProductCollectionByCategories($ids)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addCategoriesFilter(['in' => $ids]);
        return $collection;
    }

 

    public function execute()
    {
      
    
      
        $enable =  $this->_scopeConfig->getValue(
            'salesnotification/general/enable', 
              \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $key =  $this->_scopeConfig->getValue(
            'salesnotification/general/key', 
              \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if($enable == '1'):
        
            $array_from_cat = array();
            $sn = 0;
      
           
            $current_catID =  $this->request->getParam('current_catid');
            if( $current_catID > 0){
                $categoes_items = $this->getProductCollectionByCategories($current_catID);
         
                foreach($categoes_items as $item){
                    $array_from_cat[] = $item->getId();
                }

            } 
           

            $array_from_conf =  $this->_scopeConfig->getValue(
                'salesnotification/general/productids', 
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            $OptionsTypeVal =  $this->_scopeConfig->getValue(
                'salesnotification/general/type_select', 
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            
            $OptionsPositionVal =  $this->_scopeConfig->getValue(
                'salesnotification/general/position_select', 
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            
         

            if($OptionsTypeVal == 'random_cat' && $current_catID > 0){

                $array =  $array_from_cat;
                // if no items in cat, use manually product
                if(!$array){
                    $array = explode (",", $array_from_conf); 
                }

            } else if($OptionsTypeVal == 'random_manually' ){

                $array = explode (",", $array_from_conf); 

            } else if($OptionsTypeVal == 'real' ){

                $order = $this->_salesNotificationData->getOrder();

                foreach ($order->getAllVisibleItems() as $_item) {
                    $productname = $_item->getName();
                    $productId = $_item->getProductId();
                }
                $array = array($productId);
                $sn = hash('sha256', $order->getId().$key);
               
            } else {
                $array = explode (",", $array_from_conf); 
            }
            
           
            $k = array_rand($array);
            $id = $array[$k];

            //Random Message
            $array_msg = ['Just now','Just a sec ago','1 min ago'];
            $randomMsgK =  array_rand($array_msg);
            $message = $array_msg[$randomMsgK];

            $product = $this->_productloader->create()->load($id);
            $product_name = $product->getName();
            if (strlen($product_name) > 26){
                 $product_name = substr($product_name, 0, 26) . '...';
            }
            $resultJson = $this->resultJsonFactory->create();
          
      
            //$imgurl = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();
            $imgurl = $this->imageHelper->init($product, 'product_page_image_small')
            ->setImageFile($product->getSmallImage()) // image,small_image,thumbnail
            ->resize(380)
            ->getUrl();
            
            return $resultJson->setData(['product_name' => $product_name,
            'purchase_ago' => $message,
            'product_img' => $imgurl,
            'sn' => $sn,
            'product_url' => $product->getProductUrl()
        ]); 
       endif;

    }
}
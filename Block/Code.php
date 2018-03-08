<?php
/**
 * Copyright (c) 2018. Evodus.com
 * All right reserved
 */

namespace Evodus\Pixel\Block;

class Code extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;
    
    /**
     * @var \Evodus\Pixel\Helper\Data
     */
    public $helper;
    
    /**
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry;
    
    /**
     * @var \Magento\Catalog\Helper\Data
     */
    public $catalogHelper;
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Evodus\Pixel\Helper\Data $helper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Evodus\Pixel\Helper\Data $helper,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Helper\Data $catalogHelper,
        array $data = []
    ) {
        $this->storeManager  = $context->getStoreManager();
        $this->helper        = $helper;
        $this->coreRegistry  = $coreRegistry;
        $this->catalogHelper = $catalogHelper;
        parent::__construct($context, $data);
    }
    
    /**
     * Used in .phtml file and returns array of data.
     *
     * @return array
     */
    public function getPixelData()
    {
        $data = [];
    
        $data['id'] = $this->helper
            ->getConfig('evodus_pixel/general/pixel_id');
    
        $data['full_action_name'] = $this->getRequest()->getFullActionName();
    
        return $data;
    }
    
    /**
     * Returns product data needed for dynamic ads tracking.
     *
     * @return array
     */
    public function getProductData()
    {
        $p = $this->coreRegistry->registry('current_product');
    
        $data = [];
    
        $data['content_name']     = $this->helper
            ->escapeSingleQuotes($p->getName());
        $data['content_ids']      = $this->helper
            ->escapeSingleQuotes($p->getSku());
        $data['content_type']     = 'product';
        $data['value']            = number_format(
            $this->getCalculatedPrice(),
            2,
            '.',
            ''
        );
        $data['currency']         = $this->getCurrencyCode();
    
        return $data;
    }

    public function getCalculatedPrice()
    {
        $p = $this->coreRegistry->registry('current_product');
    
        $productType = $p->getTypeId();
    
        $calculatedPrice = 0;

        $tax = (int) $this->helper->getConfig('tax/display/type');
    
        if ($productType == 'configurable') {
            if ($tax === 1) {
                $calculatedPrice = $p->getFinalPrice();
            } else {
                $calculatedPrice = $this->catalogHelper->getTaxPrice(
                    $p,
                    $p->getFinalPrice(),
                    true,
                    null,
                    null,
                    null,
                    $this->storeManager->getStore()->getId(),
                    true,
                    false
                );
            }
        } elseif ($productType == 'grouped') {
            $associatedProducts = $p->getTypeInstance(true)
                ->getAssociatedProducts($p);
    
            $prices = [];
    
            foreach ($associatedProducts as $associatedProduct) {
                $prices[] = $associatedProduct->getPrice();
            }
    
            if (!empty($prices)) {
                $calculatedPrice = min($prices);
            }

        } else {
            if ($tax === 1) {
                $calculatedPrice = $p->getFinalPrice();
            } else {
                $calculatedPrice = $this->catalogHelper->getTaxPrice(
                    $p,
                    $p->getFinalPrice(),
                    true,
                    null,
                    null,
                    null,
                    $this->storeManager->getStore()->getId(),
                    false,
                    false
                );
            }
        }
    
        return $calculatedPrice;
    }

    public function getCurrencyCode()
    {
        return strtoupper(
            $this->storeManager->getStore()->getCurrentCurrency()->getCode()
        );
    }
}
<?php

namespace AllezTech\SalesNotification\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class OptionsTypeVal implements ArrayInterface
{
    public function toOptionArray()
    {

        $options = [];
        $options[] = [
            'value' => 'random_cat',
            'label' => 'Random by Current Product Category',
        ];
        $options[] = [
            'value' => 'random_manually',
            'label' => 'Random by input Product IDs below',
        ];
        $options[] = [
            'value' => 'real',
            'label' => 'Real Recent Orders',
        ];

        return $options;
    }

    public function position(){
        $options = [];
        $options[] = [
            'value' => 'bottom_left',
            'label' => 'Bottom Left',
        ];
        $options[] = [
            'value' => 'bottom_right',
            'label' => 'Bottom Right',
        ];
    }
}

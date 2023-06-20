<?php

namespace AllezTech\SalesNotification\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class OptionsPositionVal implements ArrayInterface
{
    public function toOptionArray()
    {

        $options = [];
        $options[] = [
            'value' => 'bottom_left',
            'label' => 'Bottom Left',
        ];
        $options[] = [
            'value' => 'bottom_right',
            'label' => 'Bottom Right',
        ];

        return $options;
    }

}

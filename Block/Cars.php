<?php
namespace Razoyo\CarProfile\Block;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Razoyo\CarProfile\Model\CarHandler;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Razoyo\CarProfile\System\Config\CarConfig;

class Cars extends Template
{
    /**
     * @param Context $context
     * @param CarHandler $carHandler
     * @param SerializerInterface $serializer
     * @param array $data
     */
    public function __construct(
        Context $context,
        private CarHandler $carHandler,
        private SerializerInterface $serializer,
        private CarConfig $carConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param $filter
     * @return array|bool|float|int|string|null
     * @throws NoSuchEntityException
     */
    public function getCarsList($filter){
        if ($this->carConfig->isEnabled()) {
            $cars = $this->carHandler->getCarList($filter);
            if (!$cars['error']) {
                return $this->serializer->unserialize($cars['data']);
            }
        }
        return null;
    }

    /**
     * @return mixed|null
     * @throws LocalizedException
     */
    public function getCustomerCar()
    {
        return $this->carHandler->getCustomerCar();
    }

    /**
     * @param $ptions
     * @return bool|string
     */
    public function jsonEncode($ptions) {
        return $this->serializer->serialize($ptions);
    }
}

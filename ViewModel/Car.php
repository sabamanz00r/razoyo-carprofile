<?php
namespace Razoyo\CarProfile\ViewModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Razoyo\CarProfile\Model\CarHandler;
use Magento\Framework\Serialize\SerializerInterface;
use Razoyo\CarProfile\System\Config\CarConfig;

class Car implements ArgumentInterface
{
    /**
     * @param CarHandler $carHandler
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private CarHandler $carHandler,
        private SerializerInterface $serializer,
        private CarConfig $carConfig
    ) {}

    /**
     * @return array|bool|float|int|string|null
     * @throws LocalizedException
     */
    public function getCustomerCar() {
        if ($this->carConfig->isEnabled()) {
            $customerCarValue = $this->carHandler->getCustomerCar();
            if ($customerCarValue) {
                $token = $this->carHandler->getCarList();
                if (isset($token['token']['your-token'])) {
                    $carDetails = $this->carHandler->getCarbyId($token['token']['your-token'], $customerCarValue);
                    if (is_array($carDetails)) {
                        if (!$carDetails['error']) {
                            return $this->serializer->unserialize($carDetails['data']);
                        }
                    }
                }
            }
        }
        return null;
    }
}

<?php
namespace Razoyo\CarProfile\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultInterface;
use Razoyo\CarProfile\Model\CarHandler;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Controller\ResultFactory;

class GetCar implements HttpGetActionInterface
{
    /**
     * @param RequestInterface $requestInterface
     * @param CarHandler $carHandler
     * @param SerializerInterface $serializer
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        private RequestInterface $requestInterface,
        private CarHandler $carHandler,
        private SerializerInterface $serializer,
        private ResultFactory $resultFactory
    ) {}

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $filter = $this->requestInterface->getParam('car-id');
        $make = $this->requestInterface->getParam('make');
        if ($filter && $make) {
            $token = $this->carHandler->getCarList($make);
            if (isset($token['token']['your-token'])) {
                $carDetails = $this->carHandler->getCarbyId($token['token']['your-token'], $filter);
                if (is_array($carDetails)) {
                    if (!$carDetails['error']) {
                        $carData =  $this->serializer->unserialize($carDetails['data']);
                        $carHtml = '<div class="image"><img src="' . $carData['car']['image'] . '" width="300"></div><ul class="content"><li><span>MAKE: </span> ' . $carData['car']['make'] . '</li><li><span>MODEL: </span> ' . $carData['car']['model'] . '</li><li><span>YEAR: </span> ' .$carData['car']['year'] .'</li><li><span>SEATS: </span> ' . $carData['car']['seats'] .' </li><li><span>PRICE: </span> ' . $carData['car']['price'] . ' </li><li><span>MPG: </span> ' . $carData['car']['mpg'] . '</li></ul>';
                        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
                        $result->setContents($carHtml);
                        $result->setHeader('Content-Type', 'text/html');
                        return $result;
                    }
                }
            }
        }
        $result->setContents('<span>We can\'t find this car. Please try again later.');
        $result->setHeader('Content-Type', 'text/html');
        return $result;
    }
}

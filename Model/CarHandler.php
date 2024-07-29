<?php
namespace Razoyo\CarProfile\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\HTTP\Client\Curl;
use Razoyo\CarProfile\System\Config\CarConfig;
use Magento\Customer\Model\SessionFactory;
use Psr\Log\LoggerInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CarHandler
{
    const API_CAR_MAKE_ENDPOINT = '/?make=';
    const CAR_ATTRIBUTE = 'my_car_profile';

    /**
     * @param Curl $curlClient
     * @param CarConfig $config
     * @param CustomerRepositoryInterface $customerRepository
     * @param SessionFactory $sessionFactory
     * @param LoggerInterface $logger
     * @param CarConfig $carConfig
     */
    public function __construct(
        private Curl $curlClient,
        private CarConfig $config,
        private CustomerRepositoryInterface $customerRepository,
        private SessionFactory $sessionFactory,
        private LoggerInterface $logger,
        CarConfig $carConfig
    ) {}

    /**
     * @return Curl
     */
    private function getCurlClient()
    {
        return $this->curlClient;
    }

    /**
     * @param $filter
     * @return array
     */
    public function getCarList($filter = '')
    {
        try {
            $this->getCurlClient()->setOptions(
                [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: ' . 'Bearer ' . $this->config->getAccessToken(),
                        'Content-Type: application/json'
                    ]
                ]
            );
            $url = $this->config->getCarListUrl();
            if ($filter) {
                if ($filter != 'all') {
                    $url = $this->config->getCarListUrl().self::API_CAR_MAKE_ENDPOINT.$filter;
                }
            }
            $this->getCurlClient()->get($url, []);
            $status = $this->getCurlClient()->getStatus();
            if ($status != 200) {
                $message = __('Something went wrong while getting the cars. Plese try again.');
                return [
                    'error' => true,
                    'data' => $message
                ];
            }
            return [
                'error' => false,
                'data' => $this->getCurlClient()->getBody(),
                'token' => $this->getCurlClient()->getHeaders()
            ];
        } catch (\Exception $e) {
            $message = __($e->getMessage());
            return [
                'error' => true,
                'data' => $message
            ];
        }
    }

    /**
     * @param $token
     * @param $carId
     * @return array
     */
    public function getCarbyId($token, $carId)
    {
        try {
            $this->getCurlClient()->setOptions(
                [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: ' . 'Bearer ' . $token,
                        'Content-Type: application/json'
                    ]
                ]
            );
            $url = $this->config->getCarListUrl().'/'.$carId;

            $this->getCurlClient()->get($url, []);
            $status = $this->getCurlClient()->getStatus();
            if ($status != 200) {
                $message = __('Something went wrong while getting the car. Plese try again.');
                return [
                    'error' => true,
                    'data' => $message
                ];
            }
            return [
                'error' => false,
                'data' => $this->getCurlClient()->getBody()
            ];
        } catch (\Exception $e) {
            $message = __($e->getMessage());
            return [
                'error' => true,
                'data' => $message
            ];
        }
    }

    /**
     * @return mixed|null
     * @throws LocalizedException
     */
    public function getCustomerCar() {
        $customerSession = $this->sessionFactory->create();
        if ($customerSession->isLoggedIn()) {
            try{
                $customerData = $this->customerRepository->getById($customerSession->getId());
                $customerCarAttribute = $customerData->getCustomAttribute(self::CAR_ATTRIBUTE);
                if ($customerCarAttribute) {
                    if ($customerCarAttribute->getValue()) {
                        return $customerCarAttribute->getValue();
                    }
                }
            } catch (NoSuchEntityException $e) {
                $this->logger->error($e->getMessage());
            }
        }
        return null;
    }
}

<?php
namespace Razoyo\CarProfile\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\UrlInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Message\ManagerInterface;

class Cars implements HttpPostActionInterface
{

    const CAR_ATTRIBUTE = 'my_car_profile';

    /**
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param RequestInterface $requestInterface
     * @param SessionFactory $sessionFactory
     * @param UrlInterface $urlBuilder
     * @param CustomerRepositoryInterface $customerRepository
     * @param LoggerInterface $logger
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        private PageFactory $resultPageFactory,
        private JsonFactory $resultJsonFactory,
        private RequestInterface $requestInterface,
        private SessionFactory $sessionFactory,
        private UrlInterface $urlBuilder,
        private CustomerRepositoryInterface $customerRepository,
        private LoggerInterface $logger,
        private ManagerInterface $messageManager
    ) {}

    /**
     * @return ResponseInterface|Json|ResultInterface|void
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $filter = $this->requestInterface->getParam('filter');
        $action = $this->requestInterface->getParam('action');
        if ($action == 'get') {
            $block = $resultPage->getLayout()
                ->createBlock('Razoyo\CarProfile\Block\Cars')
                ->setTemplate('Razoyo_CarProfile::cars.phtml')
                ->setData('filter', $filter)
                ->toHtml();
            $result->setData(['action'=> 'get', 'result' => $block]);
            return $result;
        }
        if ($action == 'save') {
            $customerSession = $this->sessionFactory->create();
            if ($customerSession->isLoggedIn()) {
                try {
                    $customerData = $this->customerRepository->getById($customerSession->getId());
                    $customerData->setCustomAttribute(self::CAR_ATTRIBUTE, $filter);
                    $this->customerRepository->save($customerData);
                    $this->messageManager->addSuccessMessage(__('Your car has been saved.'));
                    $result->setData(['action'=> 'save', 'result' => $this->urlBuilder->getUrl('mycar/index/index')]);
                    return $result;
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                }
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the cart. Please try again.'));
                $result->setData(['action'=> 'save', 'result' => $this->urlBuilder->getUrl('customer/account/index')]);
                return $result;
            }
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the cart. Please try again.'));
            $result->setData(['action'=> 'save', 'result' => $this->urlBuilder->getUrl('customer/account/index')]);
            return $result;
        }
    }
}

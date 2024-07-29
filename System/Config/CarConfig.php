<?php
declare(strict_types=1);
namespace Razoyo\CarProfile\System\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

class CarConfig
{
    private const XML_PATH_ENABLED = 'razoyo/razoyo_cars/enable';
    private const XML_PATH_LIST_CAR_API = 'razoyo/razoyo_cars/cars_list_api';
    private const XML_PATH_ACCESS_TOKEN = 'razoyo/razoyo_cars/access_token';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private StoreManagerInterface $storeManager
    ) {}

    /**
     * @param int|null $storeId
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isEnabled(?int $storeId = null): bool
    {
        return (bool)$this->getConfigData(self::XML_PATH_ENABLED, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCarListUrl(?int $storeId = null): string
    {
        return (string)$this->getConfigData(self::XML_PATH_LIST_CAR_API, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getAccessToken(?int $storeId = null): string
    {
        return (string)$this->getConfigData(self::XML_PATH_ACCESS_TOKEN, $storeId);
    }

    /**
     * @param string $path
     * @param int|null $storeId
     * @return string|null
     * @throws NoSuchEntityException
     */
    private function getConfigData(string $path, ?int $storeId = null): ?string
    {
        $storeId = $storeId ?? $this->storeManager->getStore()->getId();
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

}

<?php
namespace Razoyo\CarProfile\Controller\Index;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends Action implements HttpGetActionInterface {

    /**
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getPage()->getConfig()->getTitle()->set(__('My Car Profile'));
        $this->_view->renderLayout();
    }
}

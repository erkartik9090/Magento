<?php
    
    namespace Webkul\Grid\Observer;
    use Magento\Framework\Event\ObserverInterface;
	use Magento\Framework\Event\Observer;
    use Magento\Framework\App\RequestInterface;
	use Psr\Log\LoggerInterface as Logger;
 
    class CustomPrice implements ObserverInterface
    {
		 protected $_logger;
			public function __construct(
				Logger $logger
			) {
				$this->_logger = $logger;
			}
			
		public function execute(observer $observer){
			//$this->logger->debug('rrrrr');
			//Ma
			
			$item = $observer->getEvent()->getData('quote_item');
			$qty = $item->getQty();
			$this->_logger->info('Data');
			
			//$this->_logger->info($qty);exit;
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection();
			$tableName = $resource->getTableName('wk_grid_records');

//Select Data from table
			$sql = "Select * FROM " . $tableName;
			$result = $connection->fetchAll($sql);
			foreach($result as $value){
				$q_from=$value['qty_from'];
				$q_to = $value['qty_to'];
				//$this->_logger->info($q_to);exit;
				if(($qty>=$q_from) && ($qty<$q_to)){
					$price=$value['price'];
					$item->setCustomPrice($price);
					$item->setOriginalCustomPrice($price);
					$item->save(); 
			}
			}
			//$collection = $this->_objectManager->create('Webkul\Grid\Model\Grid')->getCollection();
			
			
		}
	}
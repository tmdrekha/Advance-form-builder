<?php 
namespace Opencart\Catalog\Controller\Extension\Tmdadvanceformbuilder\Startup;
require_once(DIR_EXTENSION.'tmdadvanceformbuilder/system/library/tmd/cart.php');
use \Opencart\System\Helper as Helper;
class Tmdcart extends \Opencart\System\Engine\Controller {

	private array $data = [];
	
		public function index(): void {
			
			$this->registry->set('cart', new \Opencart\Extension\Tmdadvanceformbuilder\System\Library\Tmd\Cart($this->registry));
		
		}
    
}


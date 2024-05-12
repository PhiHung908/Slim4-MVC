<?php
declare(strict_types=1);

namespace App\controllers\product;

use Hwg\hwgNav;

use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('[GET,POST]')]
class ListAction extends \hSlim\base\AbstractAction
{
	/**
	  * @var $this->model, $this->model->emRepository,  $this->model->em
	  */
	
	/** Ap dung cho tat ca Action tuong tu ListAction: Có thể dùng theo nhiều cách như auto_gen/autoName, trực tiếp như mở các rào __construct+class_ListAsset_ở_dưới hoặc global_auto_default__is__auto_gen/asset.php
	  * neu khong co construct thi se tu dong asset va model
	  * @param3: string $assetClass (null = auto_asset_in__auto_gen__dir)
	  * @param4: string $modelClass (null = auto_model_with_call_class)
	  * Hint: Co the viet truc tiep class asset tren file nay (bat buoc phai sau "ListAction", vi fastroute se ngung khi gap tu khoa class)
	  */
	//* Comment if use default asset and module. - Note: inline below, xem thêm note ở phần Class ListAsset ở dưới
	public function __construct(protected \Psr\Log\LoggerInterface &$logger, protected \Psr\Container\ContainerInterface &$c)
	{
		//parent::__construct($logger, $c); //as default: auto model and asset 
		//parent::__construct($logger, $c, 'App\controllers\product\auto_gen\ListAsset'); //asset from classFile 
		//parent::__construct($logger, $c, 'App\controllers\product\auto_gen\ListAsset', 'App\models\user\User'); //asset from classFile and another modelClassFile 
		parent::__construct($logger, $c, __NAMESPACE__ . '\ListAsset'); //is direct asset class
	}
	//*/
	
    protected function action(): Response
    {
		if (!empty($this->args) && !isset(($this->args ?? [])['Route'])) {
			throw new DomainRecordNotFoundException('Request is invalid.');
		}

		$viewData['data'] = $this->model->findAll();
        $this->logger->info("product list was viewed.");
		
		//if (!isAPI) {
			$viewData['layout'] = 'layout.php';
			return $this->render('home.php', $viewData);
			
			//return $this->render('Home.tpl', $viewData);
			//return $this->render('home.twig', $viewData);
			//return $this->fetchFromString('<h3>Ten model: <?=$model["modelName"]? ></h3>', $viewData);
			//return $this->fetchFromString('<h3>Ten model: {{ model.modelName }}</h3>', $viewData);
		//}
		
        return $this->respondWithData($viewData['data']);
    }
}

/** Mỗi action có thể có bộ asset riêng, nếu "ListAsset" là file ở auto_gen/ListAsset.php thì sẽ tự động nạp, không cần thông qua __construct ở ListAction
  * sẽ refresh cache nếu fileModeTime Class-ListAsset (này) có thay đổi
  */
class ListAsset extends \hSlim\base\AbstractAsset
{
	/** Ngoại trừ auto_gen/Asset,
	  * Các Sub-Asset BUỘC phải có contruct này khi dùng sub-asset để đánh dấu isSubAsset cho AbstractAsset nhằm không cache dup file trong auto_gen/assets
	  */
	public function __construct(&$c)
    {
		parent::__construct($c, false, true);
	}
	
	public $sourcePath = __DIR__ . "\\auto_gen\\assets";
	
    public $depends = [
		'hSlim\assets\BootstrapAsset',
		'hSlim\assets\JqueryAsset',
	];
	
    public $js = [
		'js/test2.js',
	];
    
    public $css = [
		'css/test1.css',
	];
}

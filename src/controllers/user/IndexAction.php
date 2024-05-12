<?php
declare(strict_types=1);

namespace App\controllers\user;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('GET')]
class IndexAction extends \hSlim\base\AbstractAction
{
	public function __construct(protected \Psr\Log\LoggerInterface &$logger, protected \Psr\Container\ContainerInterface &$c)
	{
		parent::__construct($logger, $c, __NAMESPACE__ . '\IndexAsset');
	}
	
//========	
	
	protected function Action(): Response
    {
		$viewData['data'] = $this->model->findAll();
        $this->logger->info("product list was viewed.");
		
		$testDirectHtml = 
		'<div class="container  mt-3">
			<div class="row justify-content-center">
				<div class="col-md-10 col-11 align-self-center border p-3">
					<h3>Welcome... Default page for <span style="color:red">' . $this->c->get('modelName') . '</span>-Model</h3>
					<br><br>Đây là nội dung của file <b>' . __FILE__ . '</b><br><br>
					Hãy vào đó để chỉnh sửa và bổ sung các sự kiện theo nhu cầu của bạn.
				</div>
			</div>
		</div>';
		$this->response->getBody()->write($testDirectHtml);
		//return $this->response;
		
		return $this->render('home.php', $viewData);
	}
}


//===========

class IndexAsset extends \hSlim\base\AbstractAsset
{
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
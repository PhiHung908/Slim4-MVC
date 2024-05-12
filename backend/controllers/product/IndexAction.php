<?php
declare(strict_types=1);

namespace App\controllers\product;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('GET')]
class IndexAction extends \hSlim\base\AbstractAction
{
	protected function Action(): Response
    {
		//if (!isAPI) {
//			return $this->render('home.twig', []);
		//}
		
		$this->response->getBody()->write('<br><h3>Welcome... Default page for <span style="color:red">product</span>-Model</h3>');
		$this->response->getBody()->write('<br><br>Đây là nội dung của file IndexAction.php trong thư mục <b>' . __DIR__ . '</b><br><br>');
		$this->response->getBody()->write('Hãy vào đó để chỉnh sửa và bổ sung các sự kiện theo nhu cầu của bạn.');
		
		return $this->response;
	}
}

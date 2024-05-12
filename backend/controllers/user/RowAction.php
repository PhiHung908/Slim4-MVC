<?php
declare(strict_types=1);

namespace App\controllers\user;


use hSlim\base\domain\domainException\DomainRecordNotFoundException;

use Psr\Http\Message\ResponseInterface as Response;

#[FastRoute('[GET,POST], {id}')]
class RowAction extends \hSlim\base\AbstractAction
{
    protected function action(): Response
    {
		if (empty($this->args)) {
			//throw new DomainRecordNotFoundException('Request is invalid.');
		}
	
        $rId = (int) $this->resolveArg('id');		
		$viewData['data'] = $this->model->findById($rId);
        $this->logger->info("user of row id `{$rId}` was viewed.");

		//if (!isAPI) {
			//$viewData['layout'] = 'layout.tpl'; //default
			//return $this->render('home.tpl', $viewData);
			
			//$viewData['layout'] = 'layout.php'; //default
			return $this->render('home.php', $viewData);
		//}
		
        return $this->respondWithData($viewData['data']);
    }
}

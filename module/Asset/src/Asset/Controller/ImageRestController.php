<?php
/**
 * Image REST controller.
 *
 * @package ComicCMS2
 * @author Cezary Kluczyński
 * @license https://github.com/cezarykluczynski/ComicCMS2/blob/master/LICENSE.txt MIT
 */

namespace Asset\Controller;

use Application\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ImageRestController extends AbstractRestfulController
{
    public function delete($id)
    {
        /** @var \Zend\View\Model\JsonModel */
        $view = new JsonModel;
        /** @var \Zend\Http\Request */
        $request = $this->getRequest();
        /** @var \Zend\Http\PhpEnvironment\Response */
        $response = $this->getResponse();

        $em = $this->getEntityManager();

        $image = $em->find('Asset\Entity\Image', (int) $id);

        if ($image)
        {
            $em->remove($image);
            $em->flush();

            return $view->setVariable('success', true);
        }
        else
        {
            $response->setStatusCode(404);
            return $view->setVariables([
                'success' => false,
                'message' => 'Image cannot be deleted, because it does not exists.',
            ]);
        }
    }
}
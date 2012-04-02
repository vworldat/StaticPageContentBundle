<?php

/*
* This file is part of the c33s\StaticPageContentBundle.
*
* (c) consistency <office@consistency.at>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace c33s\StaticPageContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/*
 * BaseStaticPageController which should be extended from.
 * 
 * Usage: MyPageController extends BaseStaticPageController
 * 
 * 
 * @author consistency <office@consistency.at>
 */
class BaseStaticPageController extends Controller
{
    /**
     * Should the Static content be sanboxed?
     * 
     * Only works for the default Content Container
     * 
     * http://twig.sensiolabs.org/doc/api.html#sandbox-extension
     * 
     * @var Boolean
     */
    protected $isSandboxed = false;
    
    
    /**
     * Returns the name of the Bundle, where the templates, which are 
     * containing the static content, are stored
     * 
     * @return string Name of the Content Bundle
     */
    protected function getContentBundleName()
    {
        return 'c33sStaticPageContentBundle';
    }
    
    /**
     * Returns the name of the folder where the content templates are stored.
     * 
     * This folder has to be located in %YourBundleName%/Resources/views
     * The default path is c33s/StaticPageContentBundle/Resources/views/Content
     * so the default return value is "Content".
     * 
     * @return string Name of the folder containing the Content
     */
    protected function getContentFolderName()
    {
        return 'Content';
    }
    
    protected function getIsSandboxed()
    {
        return $this->isSandboxed;
    }


    /**
     * Returns the full template "path" expression for a given content name. 
     * Currently only twig is implemented. The Expression includes the ":" 
     * seperators.
     * It's not the Filesystem path it's a twig path. 
     * 
     * @param string $contentName The name of the content file which should be loaded
     * @return string Full path expression for the template
     */
    protected function getContentLocation($contentName)
    {
        return sprintf
        (
            '%s:%s:%s%s', 
            $this->getContentBundleName(), 
            $this->getContentFolderName(), 
            $contentName, 
            $this->getTemplateExtension()
        );
    }
    
    /**
     * Returns template extension. Default is ".html.twig".
     * 
     * @return string Template Extension (dual extension) like ".html.twig"
     */
    protected function getTemplateExtension()
    {
        return '.html.twig';
    }
    
    /**
     * Returns the full "path" expression for the Container Template
     * 
     * @return string container template "path" expression
     */
    protected function getContainerLocation()
    {
        return 'c33sStaticPageContentBundle:Content:_content_container.html.twig';
    }
    
    /**
     *  Returns the Base Template Location which should be used for extending.
     * 
     * @return string Base Template which should be used to extend from
     *  
     */
    protected function getBaseTemplateLocation()
    {
        return '::base.html.twig';
    }
    
    /**
     * The Core Show Controller of this Bundle, renders the container templates,
     * which have to include the static page content.
     * 
     * @param string The Name of the Static Page which should be loaded
     * 
     * @return Response A Response instance
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException Not found Exception is thrown if no template with the given name exists.
     */
    public function showAction($name)
    {
        $contentLocation = $this->getContentLocation($name);
        
        if (!$this->container->get('templating')->exists($contentLocation))
        {
            throw $this->createNotFoundException();
        }
        return $this->render($this->getContainerLocation() ,
            array
            (
                'baseTemplate' => $this->getBaseTemplateLocation(), 
                'isSandboxed' => $this->getIsSandboxed(),
                'contentLocation'=> $contentLocation
            )
        );
    }
}

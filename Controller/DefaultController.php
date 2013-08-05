<?php

namespace EzSystems\SummerCamp2013PublicApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EzSystemsSummerCamp2013PublicApiBundle:Default:index.html.twig', array('name' => $name));
    }
}

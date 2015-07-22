<?php

namespace Controller;

class HomeController extends AbstractController
{
    public function indexAction()
    {
        return $this->renderView();
    }
}
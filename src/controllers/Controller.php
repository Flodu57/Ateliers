<?php
namespace mygiftbox\controllers;

class Controller{

    protected $urls, $twigParams;

    public function __construct(){
        $app = \Slim\Slim::getInstance();

        $this->urls = [
            'login' => $app->urlFor('login'),
            'forgot' => $app->urlFor('forgotpass'),
            'register'=>  $app->urlFor('register'),
            'logout' => $app->urlFor('logout'),
            'offers' => $app->urlFor('offers')
            'settings' => $app->urlFor('profile.settings'),
            'createBox' => $app->urlFor('profile.createBox'),
            'box' => $app->urlFor('profile.box', compact('slug'))
            'home' => $app->urlFor('home'),
        ];

        $this->twigParams['urls'] = $this->urls;

        if(isset($_SESSION['id_user'])){
            $this->twigParams['user_id'] = $_SESSION['id_user'];
            $this->twigParams['urls']['profile'] = $app->urlFor('profile');

        }

        if(isset($_SESSION['slim.flash']['success'])){
            $this->twigParams['error'] = $_SESSION['slim.flash']['success'];
        }

        if(isset($_SESSION['slim.flash']['error'])){
            $this->twigParams['error'] = $_SESSION['slim.flash']['error'];
        }
        

    }

    public function getRoute($name, $param){
        $app = \Slim\Slim::getInstance();
        return $app->urlFor($name, $param);
    }
}
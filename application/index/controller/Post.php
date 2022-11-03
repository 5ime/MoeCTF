<?php
namespace app\index\controller;

use app\index\model\Post as postModel;

class Post extends Base
{
    protected $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new postModel();
    }

    public function postFlag(){
        return $this->postModel->postFlag();
    }

    public function buyChallenge()
    {
        return $this->postModel->buyChallenge();
    }

}

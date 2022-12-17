<?php
namespace app\admin\controller;

use app\admin\model\Challenges as challengesModel;

class Challenges extends Base
{
    protected $challengesModel;

    public function __construct()
    {
        parent::__construct();
        $this->challengesModel = new challengesModel();
    }

    public function index()
    {
        $title = '挑战列表';
        return $this->fetch('List', ['title' => $title]);
    }

    public function add()
    {
        $title = '新增挑战';
        return $this->fetch('Add', ['title' => $title]);
    }

    public function edit()
    {
        $title = '编辑挑战';
        return $this->fetch('Edit', ['title' => $title]);
    }

    public function getChallengeList()
    {
        return $this->challengesModel->getChallengeList();
    }
    
    public function addChallenge()
    {
        return $this->challengesModel->addChallenge();
    }

    public function uploadFile()
    {
        return $this->challengesModel->uploadFile();
    }

    public function editChallengeState()
    {
        return $this->challengesModel->editChallengeState();
    }

    public function editChallenge()
    {
        return $this->challengesModel->editChallenge();
    }

    public function deleteChallenge()
    {
        return $this->challengesModel->deleteChallenge();
    }

    public function getChallengeAllInfo()
    {
        return $this->challengesModel->getChallengeAllInfo();
    }

}

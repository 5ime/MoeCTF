<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Api as apiModel;

class Api extends Controller
{
    protected $apiModel;

    public function __construct()
    {
        $this->apiModel = new apiModel();
    }

    public function getChallenges()
    {
        return $this->apiModel->getChallenges();
    }

    public function getCategory()
    {
        return $this->apiModel->getCategory();
    }

    public function getChallengeInfo()
    {
        return $this->apiModel->getChallengeInfo();
    }
    
    public function downloadFile($id){
        return $this->apiModel->downloadFile($id);
    }

    public function getSolveRank(){
        return $this->apiModel->getSolveRank();
    }

    public function getAllRank(){
        return $this->apiModel->getAllRank();
    }

    public function getAllNotify(){
        return $this->apiModel->getAllNotify();
    }

    public function getSearchInfo(){
        return $this->apiModel->getSearchInfo();
    }
}

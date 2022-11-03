<?php
namespace app\admin\controller;

use app\admin\model\Sort as SortModel;

class Sort extends Base
{
    protected $SortModel;

    public function __construct()
    {
        parent::__construct();
        $this->SortModel = new SortModel();
    }

    public function index()
    {
        $title = '分类管理';
        return $this->fetch('Index', ['title' => $title]);
    }
    
    public function getSortList()
    {
        return $this->SortModel->getSortList();
    }

    public function editSort()
    {
        return $this->SortModel->editSort();
    }

    public function deleteSort()
    {
        return $this->SortModel->deleteSort();
    }

}

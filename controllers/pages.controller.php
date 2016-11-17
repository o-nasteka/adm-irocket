<?php

class PagesController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index(){

        Router::redirect('/admin/');
        exit;

        // Get List
        $this->data['pages'] = $this->model->getList();

        // Get All Category (parent category for index page)
        $this->data['cat'] = $this->model->getAllCategories();

        // Get id,price from products for Mosquito-Sets
        $this->data['price'] = $this->model->getPrice();


    }

    public function view(){
        $params = App::getRouter()->getParams();
        if ( isset($params[0]) ){
            $alias = mb_strtolower($params[0], "UTF-8");
            $this->data['page'] = $this->model->getByAlias($alias);
            $this->data['cat'] = $this->model->getAllCategories();

            // Get Menu
            // $this->data['menu'] = $this->model->getMenu();

            // echo "<pre>";
            // print_r($this->data['cat']);
            // exit;


            // $id = strtolower($params[0]);
            // $this->data['page'] = $this->model->getById($id);
            // $this->data['cat'] = $this->model->getAllParentCategories();
        }
    }

    // For order page
    public function order(){
        $params = App::getRouter()->getParams();

        if ( isset($params[0]) ){
            $alias = mb_strtolower($params[0], "UTF-8");
            $this->data['page'] = $this->model->getByAlias($alias);
        }
    }






    public function admin_index(){
//        $this->data['pages'] = $this->model->getList();
        Router::redirect('/admin/order/');
    }

    public function admin_add(){
        if ( $_POST ){
            $result = $this->model->save($_POST);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }

        if ( isset($this->params[0]) ){
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Page was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/pages/');
    }

}
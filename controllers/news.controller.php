<?php

class NewsController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new news_m();
    }

    public function index(){
        @$params = App::getRouter()->getParams();

        if(@$params[0] == 'start'){
            $id_start = $params[1];
        }
        // выборка всех новостей
        $this->data = $this->model->list_news(@$id_start);

        // Get Menu
        $this->data['menu'] = $this->model->getMenu();


    }
        // выборка одной новости по id
    public function view(){
        $params = App::getRouter()->getParams();

        if(!count($params) && !is_numeric($params[0]) ){
            Router::redirect('/');
        }

        $id = $params[0];
        $this->data['news'] = $this->model->view_id($id);
        $this->data['news'] = $this->data['news'][0];


    }


    public function admin_index(){
        $params = App::getRouter()->getParams();
        @$id = $params[1];

        if(@$params[0] == 'start'){
            $id_start = $params[1];
        }


        if(isset($params[0],$params[1]) && $params[0] == 'delete') {

            $this->model->del_news_id($id);
            Router::redirect('/admin/news');

        }


        $this->data = $this->model->list_news_admin(@$id_start);


    }


    public function admin_add(){

        // Добавление картинки в новость
        if(isset($_POST['img_min_upld'])){
            $max_id = $this->model->add_news_image();

            Router::redirect('/admin/news/edit/'. $max_id);
        }

        if(isset($_POST['submit'],$_POST['title'],$_POST['content_min'])){

            $this->model->add_news();

            Router::redirect('/admin/news');
        }

    }




    public function admin_edit(){
        $params = App::getRouter()->getParams();
        $id = $params[0];
        $this->data['news'] = $this->model->view_id($id);
        $this->data['news'] = $this->data['news'][0];


        //Выгрузить или обновить картинку img_min
        if(isset($_POST['img_min_upld'])) {
            if (!empty($_FILES['files']['name'])) {
                if (!$this->model->img_min_upld($id)) {
                    Session::setFlash('Db not update!');

                }
                Router::redirect($_SERVER['HTTP_REFERER']);
                exit;
            }
        }
        /*
        //Выгрузить картинку img_content
        if(isset($_POST['img_content_upld'])){

            if(!$this->model-> img_content_upld($id)){

                Session::setFlash('Db not update!');

            }

        }
        */

        // Выполнить update
        if(isset($_POST['submit'])){
            $this->model->edit_news($params[0]);
            Router::redirect('/admin/news');
        }

    }

}
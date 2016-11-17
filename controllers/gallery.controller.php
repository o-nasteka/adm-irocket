<?php

class GalleryController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new gallery_m();
    }

    public function index(){
       $this->data['gallery'] = $this->model->list_gallery();

        // Get Menu
        $this->data['menu'] = $this->model->getMenu();

    }


    public function admin_index(){

        $params = App::getRouter()->getParams();
        @$id = $params[1];

        if(@$params[0] == 'start'){
            $id_start = $params[1];
        }

        if(isset($params[0],$params[1]) && $params[0] == 'delete') {

            $this->model->del_gallery_id($id);
            Router::redirect('/admin/gallery');

        }


        $this->data = $this->model-> list_gallery_admin(@$id_start);

       // echo '<pre>';
       // print_r($this->data);
       // exit;


    }
//
    public function admin_edit(){
        $params = App::getRouter()->getParams();
        $id = $params[0];
        $this->data['gallery'] = $this->model->view_id($id);
        $this->data['gallery'] = $this->data['gallery'][0];

        //print_r($this->data['gallery']);
        //exit;


        // Обновить картинку img_min
        if(isset($_POST['img_upd'])){
            if(!empty($_FILES['files']['name'])) {
                if (!$this->model->img_upd($id)) {
                    Session::setFlash('Db not update!');
                }
                Router::redirect($_SERVER['HTTP_REFERER']);
                exit;
            }
        }


        // Выполнить update
        if(isset($_POST['submit'])){
            $this->model->edit_gallery($id);
            Router::redirect('/admin/gallery');
        }

    }

    public function admin_add(){

        // Добавление картинки в галерею
        if(isset($_POST['img_min_upld'])){
            $max_id = $this->model->add_gallery_image();

            // Router::redirect('/admin/gallery/edit/'. $max_id);
            Router::redirect('/admin/gallery');
        }


    }



}

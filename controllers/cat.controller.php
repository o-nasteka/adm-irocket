<?php
class CatController extends Controller{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Cat_m();
    }

    // Admin panel index
    public function admin_index()
    {
        if (isset($_POST['sort'])) {
            @$_SESSION['sort'] = @$_POST['sort'];
        }
        if (!isset($_POST['sort']) && !isset($_SESSION['sort'])) {
            $this->data['cat'] = $this->model->getList();
        } elseif ($_SESSION['sort'] == 1) {
            $this->data['cat'] = $this->model->getList_jaluzi();
        } elseif ($_SESSION['sort'] == 2) {
            $this->data['cat'] = $this->model->getList_roleti();
        } elseif ($_SESSION['sort'] == 3) {
            $this->data['cat'] = $this->model->getList_plisse();
        } elseif ($_SESSION['sort'] == 4) {
            $this->data['cat'] = $this->model->getList_antimos();
        } elseif ($_SESSION['sort'] == 5) {
            $this->data['cat'] = $this->model->getList_out_sys();
        } elseif ($_SESSION['sort'] == 6) {
            $this->data['cat'] = $this->model->getList();
        }

    }

    // Admin add product
    public function admin_add(){
        if ( $_POST ){

            $result = $this->model->save($_POST);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/cat/');
        }
    }

    // Admin edit product
    public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/cat/');
        }

        if ( isset($this->params[0]) ){
            $this->data['cat'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/cat/');
        }
    }

    // Admin delete product
    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Page was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/cat/');
    }



}


?>
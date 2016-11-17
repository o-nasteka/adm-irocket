<?php
class ProductsController extends Controller {

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Products_m();
    }
    
    public function index(){
        $this->data['products'] = $this->model->getList();
    }

    public function view(){
       //Если нет параметра то редирект
        if( !count($params = App::getRouter()->getParams()) ){
            Router::redirect('/');
        }

        // Get Menu
        $this->data['menu'] = $this->model->getMenu();

        //Выборка по alias
        //Если есть параметр и если он не число то true иначе смотрим else
        if ( isset($params[0]) && !is_numeric($params[0]) ){

            $alias = mb_strtolower($params[0], "UTF-8");
            $this->data['products'] = $this->model->getByAlias($alias);
            $this->data['products'] = $this->data['products'][0];
            $this->data['products']['img_prod'] = $this->model->get_Img_Prod($this->data['products']['id']);

            html_head::set('title',$this->data['products']['title']);
            html_head::set('meta_key',$this->data['products']['meta_key']);
            html_head::set('meta_desc',$this->data['products']['meta_desc']);




            /* может еще пригодится
            if(isset($this->data['products'][0])){
                foreach($this->data['products'] as $data){
                }
                $this->data['products'] = $data;
            }
            */

            //Если выборка из базы false то редирект
            if(empty($this->data['products'])){
                Router::redirect('/');
            }


        }else {
            //Выборка по id
            $this->data['products'] = $this->model->getGoodsById($params[0]);
            //Если выборка из базы многомерный массив то true/
            if(isset($this->data['products'][0])){
                foreach($this->data['products'] as $data){
                }
                $this->data['products'] = $data;
            }
        }


    }

    // select all from category_sub
    public function view_sub(){

        if(!count($params = App::getRouter()->getParams())){
            Router::redirect('/');
        }

        $this->data['sub'] = $this->model-> list_sub_cat($params[0]);

        if(count($this->data['sub'])){

            $this->data['contrl'] = 'view_sub';
        }else{

            $this->data['contrl'] = 'view';
            $this->data['sub'] = $this->model->list_prod_sub_cat($params[0]);
        }

        // Get Menu
        $this->data['menu'] = $this->model->getMenu();


       // echo '<pre>';
       // print_r($this->data['sub']);
        // exit;

    }


    // All SubCategory calculator
    public function view_subcategory(){
        if(count($params = App::getRouter()->getParams())){
            Router::redirect('/');
        }

        // Get Menu
        $this->data['menu'] = $this->model->getMenu();

        $this->data['sub_all'] = $this->model->getAllCategorySub();
    }




    // Admin panel index
    public function admin_index(){
        if(isset($_POST['sort'])){
            @$_SESSION['sort'] = @$_POST['sort'];
        }
        if(!isset($_POST['sort']) && !isset($_SESSION['sort'])) {
            $this->data['products'] = $this->model->getList();
        }elseif($_SESSION['sort'] == 1){
            $this->data['products'] = $this->model->getList_jaluzi();
        }elseif($_SESSION['sort'] == 2){
            $this->data['products'] = $this->model->getList_roleti();
        }elseif($_SESSION['sort'] == 3){
            $this->data['products'] = $this->model->getList_plisse();
        }elseif($_SESSION['sort'] == 4){
            $this->data['products'] = $this->model->getList_antimos();
        }elseif($_SESSION['sort'] == 5){
            $this->data['products'] = $this->model->getList_out_sys();
        }elseif($_SESSION['sort'] == 6){
            $this->data['products'] = $this->model->getList();
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
            Router::redirect('/admin/products/');
        }
    }

    // Admin edit product
    public function admin_edit(){
        $params = App::getRouter()->getParams();

     /* Сохранить порядок кода */
       // Обновление изображения
        if(isset($_POST['img_prod_upd'])){
            if(!empty($_FILES['files']['name'])) {
                $this->model->img_prod_upd($_POST['id']);
                Router::redirect('/admin/products/edit/' . $_POST['id_prod']);
            }
           // Router::redirect('/admin/products/edit/' . $_POST['id_prod']);
        }

        // Удаление изображениея
        if(isset($params[0],$params[1]) && $params[0] == 'delete'){

            $this->model->del_img_prod_id($params[1]);
            Router::redirect($_SERVER['HTTP_REFERER']);
        }
        // Вход в шаблон обновления изображения
        if(isset($params[0],$params[1]) && $params[0] == 'img_prod_list'){
            $this->data['img_prod'] = $this->model->img_prod_view($params[1]);
            $this->data['img_prod'] = $this->data['img_prod'][0];
            $this->data['products_id'] = $params[2];
           // echo '<pre>';
           // print_r($this->data);
           // exit;

            return VIEWS_PATH.DS.'products'.DS.'admin_prod_img_upd.html';
        }
        /* Сохранить порядок кода конец */



        if ( $_POST ){
            // Добавление картинки в img_prod
            if(!empty($_FILES['files']['name'])){
                $this->model->add_gallery_image($this->params[0]);
                Router::redirect('/admin/products/edit/'.$this->params[0]);
            }

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/products/');
        }


        if ( isset($this->params[0]) ){
            $this->data['products'] = $this->model->getById($this->params[0]);
            $this->data['products']['img_prod'] = $this->model->get_Img_Prod($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/products/');
        }

           // echo '<pre>';
           // print_r($this->data['products']['img_prod']);
           // exit;
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
        Router::redirect('/admin/products/');
    }



}



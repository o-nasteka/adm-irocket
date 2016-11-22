<?php

// Soft pdf crater include

//require ($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
//use Dompdf\Dompdf;
//use Dompdf\Options;
// Soft pdf crater include END

class OrderController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Order_m();

    }


    public function admin_index(){


        $params = App::getRouter()->getParams();

        if(isset($_POST['ticket_checket_click'])){

            if(!count($_POST['ticket_check'])){
                Router::redirect('/admin/order/');
            }

            // func ramd number invoive
            function rand_num(){
            //    $letter = chr(rand(97,122));
                $letter_1 = chr(rand(65,90));
                $letter_2 = chr(rand(65,90));
                $rand_numb = rand(0,100000);

                $number = $letter_1 . $letter_2 . $rand_numb ;
                return $number;

            }
            // func ramd number invoive END

//            echo '<pre>';
//            print_r ($_POST);
//            echo '</pre>';
////            exit;

            $res_email = $this->data = $this->model->ticket($_POST['ticket_check']);

            if($res_email){

                $email_count = count($res_email);
                $email_count = $email_count - 1;
                for($i = 0 ; $i <= $email_count; $i++){
                    $res_email[$i]['number']  = rand_num();

                }



                // to base number
                $this->data = $this->model->ticket_to_base($res_email);


                $_SESSION['email_check'] = $res_email ;

                Router::redirect('/webroot/ticket/ticket.php');

            }



        }


        if(!isset($params[0])){
            unset($_SESSION['search_string']);
        }
        // Параметры поиска
        if(isset($_POST['search_string']) ){
            $search_string = $_POST['search_string'];
            $_SESSION['search_string'] = $_POST['search_string'];
        }elseif(isset($_SESSION['search_string'])){
            $search_string = $_SESSION['search_string'];
        }else {
            $search_string = NULL;
        }
        // Параметры поиска END



        // Пагинация установка
        if(@$params[0] == 'start'){
            $id_start = $params[1];
        }
        // Пагинация установка END


        //Сортировка установка
        if(isset($_POST['sotrin_status'])){
            $_SESSION['sort_status'] = $_POST['sotrin_status'];
            $sorting_status =  $_SESSION['sort_status'];
        }elseif(isset($_SESSION['sort_status'])){
            $sorting_status =  $_SESSION['sort_status'];
        }else {
            $sotrin_status_default = 10;
        }


        if(isset($_POST['sotrin_date'])){
            $_SESSION['sort_date'] = $_POST['sotrin_date'];
            $sorting_date =  $_SESSION['sort_date'];
        }elseif(isset($_SESSION['sort_date'])){
            $sorting_date =  $_SESSION['sort_date'];
        }else {
            $sotrin_date_default = 10;
        }

        //Сортировка установка END

        $this->data = $this->model->getList(@$id_start , @$sorting_status, @$sorting_date,$search_string);


        if(isset($sotrin_status_default)){
            $this->data['items_sort_status'] = $sotrin_status_default;
        }else {
            $this->data['items_sort_status'] = $sorting_status;
        }

        if(isset($sotrin_date_default)){
            $this->data['items_sort_date'] = @$sotrin_date_default;
        }else {
            $this->data['items_sort_date'] = @$sorting_date;
        }


        // Запомнить номер страници
        $_SESSION['uri_pag'] = $_SERVER['REQUEST_URI'];
        // Запомнить номер страници END



    }

    public function index(){

        // Get Menu
        $this->data['menu'] = $this->model->getMenu();

        if ( $_POST ){

            if ( !isset($data['name']) || !isset($data['phone']) || !isset($data['title'])){

                $this->model->sendEmail($_POST);
                $this->model->SaveOrder($_POST);

            }
        }
    }

    // Admin edit order
    public function admin_edit(){



        if ( $_POST ){

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect($_POST['redirect']);
        }



        if ( isset($this->params[0]) ){
            $this->data['order'] = $this->model->getById($this->params[0]);

        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/order/');
        }
    }


    // Admin delete order
    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Page was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/order/');
    }


    // Admin delete order
    public function admin_add_ord(){

        if(isset($_POST['add_ord'])){
            $result = $this->model->save2($_POST);



            Router::redirect($_SERVER['HTTP_REFERER']);
        }



    }


}
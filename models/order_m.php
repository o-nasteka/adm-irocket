<?php

class Order_m extends Model {

    // getMenu
    public function getMenu(){
        $sql = "select * from `products` ";
        return $this->db->query($sql);
    }

    public function sendEmail($data){

        $mail = new PHPMailer;

        $mail->CharSet = 'UTF-8';
        // $mail->setLanguage('ru');
        // $mail->SetLanguage("ru","phpmailer/language");
        $mail->setFrom('info@mail.ua', 'Order');
        $mail->Subject = 'Новая заявка';

        $mail->AddBCC('oleg.nasteka@gmail.com', 'Oleg Nasteka');     // Add a recipient
        // $mail->AddBCC('oleg.nasteka@gmail.com', 'Oleg Nasteka');  //  Скрытая копия BCC
        // $mail->addAddress('ellen@example.com');               // Name is optional
        $mail->addReplyTo('info@mail.ua', 'Order');

        $mail->isHTML(true);                                  // Set email format to HTML




        $mess = '
        <h3>New order</h3>
        <table border="0">
            <tbody>
                <tr>
                    <td><strong>Name: </strong></td>
                    <td>'.$data['name'].'</td>
                </tr>
                <tr>
                    <td><strong>Phone: </strong></td>
                    <td>'.$data['phone'].'</td>
                </tr>
                <tr>
                    <td><strong>Title: </strong></td>
                    <td>'.$data['title'].'</td>
                </tr>
            </tbody>
        </table>';

        //
        $mail->Body=$mess;

        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo '';
        }

    }

    //  Save order
    public function SaveOrder($data, $id = null){
        if ( !isset($data['name']) || !isset($data['phone']) || !isset($data['title'])){
            return false;
        }

        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $phone = $this->db->escape($data['phone']);
        $title = $this->db->escape($data['title']);
        $date = date("Y-m-d H:i:s");

        if ( !$id ){ // Add new record
            $sql = "
                insert into `order`
                   set name = '{$name}',
                       phone = '{$phone}',
                       title = '{$title}',
                       date = '{$date}'
            ";
        } else { // Update existing record
            $sql = "
                update `order`
                   set name = '{$name}',
                       phone = '{$phone}',
                       title = '{$title}'

                   where id = {$id}
                   //
            ";
        }

        return $this->db->query($sql);

    }
//
    public function getListSearch($search){

        $search = $this->db->escape($search);

        // Запрос для выборки целевых элементов:
        $sql = "SELECT *, MATCH `city`, `name`, `phone`, `email`, `price`, `package`, `from_form`, `utm`, `comment1`, `comment2`,`org_name` AGAINST ('$search') as relev FROM `order` WHERE MATCH `city`, `name`, `phone`, `email`, `price`, `package`, `from_form`, `utm`, `comment1`, `comment2`,`org_name` AGAINST ('$search') > 0 ORDER BY relev DESC";

        $res  = $this->db->query($sql);

    }

    public function ticket($data){

//        $search = $this->db->escape(data);
        $string_id = '';
        foreach($data as $k=>$v){
            $string_id .= "$v,";

        }
        $string_id = substr($string_id, 0, -1);



        $sql =" SELECT `id`,`email` FROM `order` WHERE `id` in($string_id)";

        $res  = $this->db->query($sql);

        return $res;
    }


    public function ticket_to_base($data){

        foreach($data as $k=>$v){

            $sql =" update `order`
                   set numb_ticket = '{$v['number']}'

                   where `id` = '{$v['id']}'";

            $res  = $this->db->query($sql);

        }



    }


    public function getList($id_start = null,$sorting_status = NULL,$sorting_date = NULL, $search_string = NULL){
        // Результирующий массив с элементами, выбранными с учётом LIMIT:
        $items    = array();

        // Число вообще всех элементов ( без LIMIT ) по нужным критериям.
        $allItems = 0;

        // HTML - код постраничной навигации.
        $html     = NULL;

        // Количество элементов на странице.
        // В системе оно может определяться например конфигурацией пользователя:
        $limit    = 10
        ;
        $res['limit'] = $limit;
        // Количество страничек, нужное для отображения полученного числа элементов:
        $pageCount = 0;

        // Содержит наш $params[1] -параметр из строки запроса.
        // У первой страницы его не будет, и нужно будет вместо него подставить 0!!!
        $start    = isset($id_start)  ? (int)$id_start    : 0 ;
        $res['start'] = $start;

        // Сортировка
        if($sorting_status == 10){
            $sorting_status = NULL;
            unset($_SESSION['sort_status']);
        }

        if($sorting_date == 10){
            $sorting_date = NULL;
            unset($_SESSION['sort_date']);
        }



        if($sorting_status == NULL && $sorting_date == NULL){
            $sort_db = 'ORDER BY `id` DESC';
        }elseif($sorting_status != null && $sorting_date == null){

            if($sorting_status == 0) {
                $sort_db = 'ORDER BY `status` = 0 DESC, `id` DESC';
            }
            if($sorting_status == 1) {
                $sort_db = 'ORDER BY `status` = 1 DESC, `id` DESC';
            }
            if($sorting_status == 2) {
                $sort_db = 'ORDER BY `status` = 2 DESC, `id` DESC';
            }
            if($sorting_status == 3) {
                $sort_db = 'ORDER BY `status` = 3 DESC, `id` DESC';
            }
            if($sorting_status == 4) {
                $sort_db = 'ORDER BY `status` = 4 DESC, `id` DESC';
            }
        }elseif($sorting_status == null && $sorting_date != null){

            if($sorting_date == 0) {
                $sort_db = 'ORDER BY `id` DESC';
            }
            if($sorting_date == 1) {
                $sort_db = 'ORDER BY `date` ASC';
            }
            if($sorting_date == 2) {
                $sort_db = 'ORDER BY `date` DESC';
            }

        }elseif($sorting_status != null && $sorting_date != null){


            if($sorting_status == 1) {
                $sort_status_tmp = 'ORDER BY `status` = 1 DESC, `id` DESC';
            }
            if($sorting_status == 2) {
                $sort_status_tmp = 'ORDER BY `status` = 2 DESC, `id` DESC';
            }
            if($sorting_status == 3) {
                $sort_status_tmp = 'ORDER BY `status` = 3 DESC, `id` DESC';
            }
            if($sorting_status == 4) {
                $sort_status_tmp = 'ORDER BY `status` = 4 DESC, `id` DESC';
            }


            if($sorting_date == 1) {
                $sort_date_tmp = ',`date` DESC';
            }
            if($sorting_date == 2) {
                $sort_date_tmp = ', `date` ASC';
            }

            $sort_db = $sort_status_tmp . $sort_date_tmp;

        }
        // Сортировка END


    if($search_string != NULL) {

        $search = $this->db->escape($search_string);

        // Запрос для выборки целевых элементов:
        $sql = "SELECT *, MATCH `city`, `name`, `phone`, `email`, `price`, `package`, `from_form`, `utm`, `comment1`,
        `comment2`,`org_name`, `numb_ticket` AGAINST ('$search') as relev FROM `order` WHERE MATCH `city`, `name`, `phone`, `email`, `price`, `package`,
        `from_form`, `utm`, `comment1`, `comment2`,`org_name`, ` numb_ticket` AGAINST ('$search') > 0 ORDER BY relev DESC LIMIT $start,$limit";


        $res['item']  = $this->db->query($sql);

    }else {

        // Запрос для выборки целевых элементов:
        $sql = 'SELECT           ' .
            ' * 				 ' .
            'FROM             ' .
            '  `order`     ' .
            $sort_db . // сортировка

            ' LIMIT            ' .
            $start . ',   ' . $limit . '

             ';

        //        echo $sorting_status .'<br>';
        //        echo $sorting_date .'<br>';
        //
        //        echo $sql;
        //        exit;


        $res['item'] = $this->db->query($sql);

    }


        // СОБСТВЕННО, ПОСТРАНИЧНАЯ НАВИГАЦИЯ:
        // Получаем количество всех элементов:
        $sql = 'SELECT         ' .
            '  COUNT(*) AS `count` ' .
            'FROM           ' .
            '  `order` '
        ;
        $stmt  = $this->db->query($sql);
        $allItems = $stmt[0]['count'];
        $res['count'] =$allItems;



        // Здесь округляем в большую сторону, потому что остаток
        // от деления - кол-во страниц тоже нужно будет показать
        // на ещё одной странице.
        $pageCount = ceil( $allItems / $limit);

        // Сортировка в пагинацию
//        if(isset($sorting_status)){
//            $sort_stat_link = '/status_sort/'. $sorting_status;
//        } if(isset($sorting_date)){
//            $sort_date_link = '/date_sort/'. $sorting_date;
//        }
        // Сортировка в пагинацию END

        for( $i = 0; $i < $pageCount; $i++ ) {
            // Здесь ($i * $limit) - вычисляет нужное для каждой страницы  смещение,
            // а ($i + 1) - для того что бы нумерация страниц начиналась с 1, а не с 0
            if($start == ($i * $limit)) {
                @$res['html'] .= '<li class="active" ><a href="/admin/order/index/start/' . ($i * $limit) .' ">' . ($i + 1) . '<span class="sr-only">(current)</span></a></li>';
            }else {
                @$res['html'] .= '<li><a href="/admin/order/index/start/' . ($i * $limit) .' ">' . ($i + 1) . '</a></li>';
            }
        }
//
        // echo '<pre>';
        // print_r($res);
        // exit;

        return $res;

        // $sql = "select * from `order` where 1";
        // return $this->db->query($sql);
    }

    // Get all by Id from table order
    public function getById($id){
        $id = (int)$id;
        $sql = "select * from `order` where `id` = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }


    // Save to table order -
    public function save($data, $id = null){
//        if ( !isset($data['name']) || !isset($data['phone']) || !isset($data['title']) || !isset($data['status'])){
//            return false;
//        }

        // delete 'space';
        $data = $this->db->trimAll_l($data);

        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $phone = $this->db->escape($data['phone']);
        $title = $this->db->escape($data['title']);
        $status = $this->db->escape($data['status']);
        $date = date("Y-m-d H:i:s");;



        if ( !$id ){ // Add new record
//            $sql = "
//                insert into `order`
//                   set name = '{$name}',
//                       phone = '{$phone}',
//                       title = '{$title}',
//                       status = '{$status}',
//                       date = '{$date}'
//
//            ";


        } else { // Update existing record
            $sql = "
                update `order`
                   set name = '{$_POST['name']}',
                       date = '{$_POST['date']}',
                       org_name = '{$_POST['org_name']}',
                       city = '{$_POST['city']}',
                       email = '{$_POST['email']}',
                       price = '{$_POST['price']}',
                       package = '{$_POST['package']}',
                       from_form = '{$_POST['from_form']}',
                       comment1 = '{$_POST['comment1']}',
                       comment2 = '{$_POST['comment2']}',
                       phone = '{$_POST['phone']}',
                       user_name = '{$_SESSION['login']}',
                       status = '{$_POST['status']}'

                   where `id` = {$id}
            ";
        }

        return $this->db->query($sql);
    }


// Save to table order -
    public function save2($data, $id = null)
    {


//        $data = $this->db->trimAll_l($data);

        $id = (int)$id;

        $date = date("Y-m-d H:i:s");;


        $sql = "
                insert into `order`
                   set name = '{$_POST['name']}',
                       org_name = '{$_POST['org_name']}',
                       city = '{$_POST['city']}',
                       phone = '{$_POST['phone']}',
                       email = '{$_POST['email']}',
                       package = '{$_POST['package']}',
                       from_form = '{$_POST['from_form']}',
                       comment1 = '{$_POST['comment1']}',
                       comment2 = '{$_POST['comment2']}',
                       status = '{$_POST['status']}',
                       utm = '{$_POST['utm']}',
                       user_name = '{$_SESSION['login']}',
                       date = '{$date}'

            ";

        return $this->db->query($sql);


    }




    public function delete($id){
        $id = (int)$id;
        $sql = "delete from `order` where `id` = {$id}";
        return $this->db->query($sql);
    }

}

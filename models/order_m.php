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
        $date = date("Y-m-d");

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
    public function getList($id_start = null){
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


        // Запрос для выборки целевых элементов:
        $sql = 'SELECT           ' .
            ' * 				 ' .
            'FROM             ' .
            '  `order`     ' .
            'ORDER BY `id` DESC    ' . // отсротировать от последннего заказа

            'LIMIT            ' .
            $start . ',   ' . $limit   . '

             ';


        $res['item']  = $this->db->query($sql);




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

        for( $i = 0; $i < $pageCount; $i++ ) {
            // Здесь ($i * $limit) - вычисляет нужное для каждой страницы  смещение,
            // а ($i + 1) - для того что бы нумерация страниц начиналась с 1, а не с 0
            if($start == ($i * $limit)) {
                @$res['html'] .= '<li class="active" ><a href="/admin/order/index/start/' . ($i * $limit) . '">' . ($i + 1) . '<span class="sr-only">(current)</span></a></li>';
            }else {
                @$res['html'] .= '<li><a href="/admin/order/index/start/' . ($i * $limit) . '">' . ($i + 1) . '</a></li>';
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
        $date = date("Y-m-d");;



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
                       package = '{$_POST['package']}',
                       from_form = '{$_POST['from_form']}',
                       comment1 = '{$_POST['comment1']}',
                       comment2 = '{$_POST['comment2']}',
                       phone = '{$_POST['phone']}',
                       user_name = '{$_SESSION['login']}',
                       utm = '{$_SESSION['utm']}',
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

        $date = date("Y-m-d");;


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
                       user_name = '{$_SESSION['login']}',
                       utm = '{$_SESSION['utm']}',
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

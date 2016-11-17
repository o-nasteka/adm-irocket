<?php
class gallery_m extends Model{

    public function list_gallery(){

        $sql = "SELECT * FROM `gallery`";
        return $this->db->query($sql);

    }

    // getMenu
    public function getMenu(){
        $sql = "select * from `products` ";
        return $this->db->query($sql);
    }


    public function list_gallery_admin($id_start = null){

    // Результирующий массив с элементами, выбранными с учётом LIMIT:
        $items    = array();

    // Число вообще всех элементов ( без LIMIT ) по нужным критериям.
        $allItems = 0;

    // HTML - код постраничной навигации.
        $html     = NULL;

    // Количество элементов на странице.
    // В системе оно может определяться например конфигурацией пользователя:
        $limit    = 5;
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
            '  `gallery`     ' .

            'LIMIT            ' .
            $start . ',   ' . $limit   . '

             ';
        //$sql = " SELECT * FROM `gallery` LIMIT '".$start."', '".$limit."' ";

        $res['gallery']  = $this->db->query($sql);




    // СОБСТВЕННО, ПОСТРАНИЧНАЯ НАВИГАЦИЯ:
    // Получаем количество всех элементов:
        $sql = 'SELECT         ' .
            '  COUNT(*) AS `count` ' .
            'FROM           ' .
            '  `gallery` '
        ;
        $stmt  = $this->db->query($sql);
        $allItems = $stmt[0]['count'];
        $res['count'] =$allItems;



    // Здесь округляем в большую сторону, потому что остаток
    // от деления - кол-во страниц тоже нужно будет показать
    // на ещё одной странице.
        $pageCount = ceil( $allItems / $limit);

    // Начинаем с нуля! Это даст нам правильные смещения для БД
        for( $i = 0; $i < $pageCount; $i++ ) {
            // Здесь ($i * $limit) - вычисляет нужное для каждой страницы  смещение,
            // а ($i + 1) - для того что бы нумерация страниц начиналась с 1, а не с 0
           if($start == ($i * $limit)) {
               @$res['html'] .= '<li class="active" ><a href="/admin/gallery/index/start/' . ($i * $limit) . '">' . ($i + 1) . '<span class="sr-only">(current)</span></a></li>';
           }else {
               @$res['html'] .= '<li><a href="/admin/gallery/index/start/' . ($i * $limit) . '">' . ($i + 1) . '</a></li>';
           }
        }

        return $res;


    }

    // Выборка по id
    public function view_id($id){
        $id = (int)$id;
        $sql = " SELECT * FROM `gallery` WHERE `id` = '{$id}' LIMIT 1 ";

        return $this->db->query($sql);
    }

    // Редактирование эллемента галереи
    public function edit_gallery($id){

        $id = (int)$id;

        // Удаляет пробелы справа и слева, и применяет mysqli_escape_string к массиву POST
        foreach($_POST as $k=>$v) {
            $_POST[$k] = $this->db->escape(trim($v));
        }

        $sql = "
		UPDATE `gallery` SET
		`title`       = '".($_POST['title'])."'
		WHERE `id` = ".$id."
	";
        return $this->db->query($sql);
    }

    public function img_upd($id){
        $id = (int)$id;

        $sql = "SELECT `img` FROM `gallery` WHERE `id` = '{$id}' ";
        $sql_tmp = $this->db->query($sql);
        if($sql_tmp){
            // Указываем полный путь
            $sql_tmp = ROOT . DS . $sql_tmp[0]['img'];
            // Удаляем предыдущий файл картинки
            unlink($sql_tmp);
        }

        // Путь для загрузки файла
        $path = ROOT.DS.'webroot'.DS.'uploads'.DS.'images'.DS.'gallery'.DS;

        // Создаем обькт передаем путь в конструктор, и загружаем файл по указоному пути
        $img_upl_obj = new img_upload($path);
        // Получаем полный путь и имя файла
        $path_full = $img_upl_obj->get_path_full();

        unset($img_upl_obj);
        // Обрезаем до /webroot
        $path_full = stristr($path_full, "/webroot");

        // Обновляем базу с новой картинкой
        $sql = "
    	UPDATE `gallery` SET
    	`img`	= '".($path_full)."'
    	WHERE `id`  =  ".$id." ";

        return $this->db->query($sql);

    }

    // Добавление отдельно картинки (создание новой картинки в галереи)
    public function add_gallery_image(){

        // Путь для загрузки файла
         $path = ROOT.DS.'webroot'.DS.'uploads'.DS.'images'.DS.'gallery'.DS;
        //$path = ROOT.DS.'webroot'.DS.'uploads'.DS ;
        // Создаем обькт передаем путь в конструктор, и загружаем файл по указоному пути
        $img_upl_obj = new img_upload($path);
        // Получаем полный путь и имя файла
        $path_full = $img_upl_obj->get_path_full();

        unset($img_upl_obj);
        // Обрезаем до /upld
        $path_full = stristr($path_full, "/webroot");
       // $path_full = stristr($path_full, "/uploads");


        $sql = "
		INSERT INTO `gallery` SET
		`img`       = '".($path_full)."'
	";

        if($this->db->query($sql)){
            // Узнать последний id
            $sql = "SELECT MAX(id) FROM `gallery`";
            $tmp_sql =$this->db->query($sql);
            $max_id = $tmp_sql[0]['MAX(id)'];

            return $max_id;
        }


    }



    // Удаление по id
    public function del_gallery_id($id){
        $id = (int)$id;

        $sql = " SELECT `img` FROM `gallery` WHERE `id` = '{$id}' ";
        $sql_tmp = $this->db->query($sql);
        // Указываем полный путь
        $sql_tmp = ROOT . DS . $sql_tmp[0]['img'];
        // Удаляем файл картинки
        unlink($sql_tmp);

        $sql = " DELETE FROM `gallery` WHERE `id` = '{$id}' ";

        return $this->db->query($sql);

    }




}


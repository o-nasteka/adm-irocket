<!--
<form action="" method="post" enctype="multipart/form-data" >
<input type="file" name="files" >
<input type="submit" name="submit_upl" value="Upload files" >
</form>
-->
<?php 
// $path = '/images/uploads';
// создаем обькт передаем путь в конструктор, возвращает полный путь и имя файла
// $path_name = new img_upload($path);  
?>

<?php
/*
In (.htaccess)
    php_value memory_limit 128M
    php_value upload_max_file_size 50M
    php_value post_max_size 50M
    php_value max_input_time 3000

*/

class Img_upload{

// Допустимый mime тип
	private $array_mime = array('image/gif', 'image/jpg', 'image/jpeg', 'image/png');
// Допустимые расширения файлов
	private $array_ext = array('jpeg','jpg','gif','png');

// Имя файла без разширения
	private $file_name;
// Разширение с точкой входящего файла
	private $file_ext_dot;
// Разширение без точи входящего файла
	private $file_ext;


// Не меньше чем байт
// private $size_down = 5000;
// Не больше чем байт
	private $size_up = 5000000;
//Путь куда будет загружен файл
	private $path;
// $path + $_FILES['files']['name'] + rand
	private $path_full;

// Имя файла
//private $name = $_FILES['files']['name'];

// Формирование пути и именни файла с помощью date() и rand;
// $name = date('Ymd_His') . 'img' . rand(10000,99999) . $file_ext_dot;


	public function __construct($path){

		$this-> set_path($path);
		$this-> upload();

	}



	public function get_path_full(){
		return $this->path_full;
	}

	private function set_path($path){
		$this->path = $path;
	}




	private function upload(){

		// вырезаем разширение файла с точкой
		$this->file_ext_dot  = strrchr($_FILES['files']['name'],".");

		if(!$this->file_ext_dot){ ?>
			<p><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><button class="btn btn-sm btn-primary" style="padding: 5px 20px;">Вернуться назад</button></a></p>
			<?php exit('Not correct file');

		}
		// Имя файла без разширения
		$tmp_file = $_FILES['files']['name'];
		$pos = mb_strripos($tmp_file,'.',0,'UTF-8');
		$this->file_name = $tmp = mb_substr($tmp_file,0,$pos,"UTF-8");
		// Имя файла без разширения конец

		// Кир - лат
		$this->file_name = $this->cyr_lat($this->file_name);

		// вырезаем точку в раширении файла
		$this->file_ext = mb_substr($this->file_ext_dot,1);
		$this->file_ext = mb_strtolower($this->file_ext,"UTF-8");

		// Проверяем на допустимые разширения
		if(!in_array($this->file_ext,$this->array_ext)){
			exit('Not support file!');
		}

		// Если ошибок нет
		if($_FILES['files']['error'] == 0){

			if($_FILES['files']['size'] < $this->size_up){

				// Получает свойства загружаемой картинки
				$temp = getimagesize($_FILES['files']['tmp_name']);
				// Проверяем на допустимый mime тип
				if(in_array($temp['mime'],$this->array_mime)){

					// Формирование пути и имени файла
					$this->path_full = $this->path . $this->file_name . '_' . rand(10000,99999) . $this->file_ext_dot;


					// Выгрузить временной файл по сформировавщемуся пути $this->path_full
					if(move_uploaded_file($_FILES['files']['tmp_name'],$this->path_full)){
						echo 'Upload comlate';
						Session::setFlash('Upload comlate!!!');

					}else{
						exit('Upload in correct');
					 }

				}else{
					exit('mime not correct');

				 }
			}else{
				// size byte/1024 = size kbyte;
				exit('size file not correct');
			}


		 }else{
			exit('errors upload files');
		 }

	}


		private function cyr_lat($textcyr = null, $textlat = null) {
			$cyr = array(
				'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
				'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я');
			$lat = array(
				'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
				'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q');
			if($textcyr) return str_replace($cyr, $lat, $textcyr);
			else if($textlat) return str_replace($lat, $cyr, $textlat);
			else return null;
		}




}


?>


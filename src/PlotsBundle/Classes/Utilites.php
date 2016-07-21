<?php
namespace PlotsBundle\Classes;
use Exception;
use ErrorException;
use DateTime;
use DateTimeZone;
//use Doctrine\ORM\Query\
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of utilites
 *
 * @author fier
 */
class Utilites{
    //КОНСТАНТА ДЛЯ ОТОБРАЖЕНИЯ ГРАФИКОВ, ПО-УМОЛЧАНИЮ
    const param_default="Температура на поверхности за ";
    /*
     * ФУНКЦИЯ, КОТОРАЯ ВОЗВРАЩАЕТ ТЕКУЩИЙ ГОД, МЕСЯЦ, ЧИСЛО ,ДЕНЬ и ЧАС
     * В ЗАВИСИМОСТИ ОТ ВХОДНОГО ПАРАМЕТРА
    */ 
    public static function get_datas_for_table ($param) {
        switch ($param) {
            case "YYYY":
                return date("Y");
            break;
            case "MM":
                return date("m");
            break;
            case "DD":
                return date("d");
            break;
            case "HH":
                return date("H");
            break;
            default:
            break;
        }
    }
    /*
     * ПЕРЕМЕННЫЕ ДЛЯ ХРАНЕНИЯ РАЗЛИЧНЫХ ВЫБОРОК МЕТЕОДАННЫХ
     * 
     */
    public static $exchange_selection=array(
                array(
                    "value"=>1,
                    "string"=>"За сегоднящний день"
                ),
                array(
                    "value"=>2,
                    "string"=>"За вчерашний день"
                ),
                array(
                    "value"=>7,
                    "string"=>"За прошедшую неделю"
                ),
                array(
                    "value"=>30,
                    "string"=>"За последний месяц"
                ),
                array(
                    "value"=>"half_year",
                    "string"=>"За пол года"
                ),
                array(
                    "value"=>"year",
                    "string"=>"За год"
                ),
            );
    //запрос из таблицы meteo, param_1-атмосферный параметр для запроса, date - дата запроса
    public static function get_data_from_meteo($param_1,$date) {
        $db_statement="SELECT `Timestamp` as `datetime`,`$param_1` FROM `meteo` WHERE DATE(`timestamp`)=?";
        global $mysqli;
        if ($stmt = $mysqli->prepare($db_statement)) {
            $stmt->bind_param("s",$date);
            $stmt->execute();
            $stmt->bind_result($col1, $col2);
            if ($stmt->field_count) {
                while ($stmt->fetch()) {
                        $result[]=array(0=>strtotime($col1)*1000+4*60*60*1000,1=>$col2);
                    }
                    $stmt->close();
                    return $result;
            }
            else { 
                $result="Ошибка!";
            }
            $mysqli->close();
            return $result;
        }
        else {
            return "Ошибка!";
        }
    }
    /*
     * ПАРАМЕТРЫ ДЛЯ ВЫПАДАЮЩИХ СПИСКОВ, CHECKBOX'ОВ И Т.Д.
     */
    //1-ПАРАМЕТРЫ С АМС
    public static function get_for_selects () {
    $options_param=array(
        array(
            "value"=>"T",
            "name"=>"Температура, С"
        ),
        array(
            "value"=>"P",
            "name"=>"Атмосферное давление, гПа"
        ),
        array(
            "value"=>"F",
            "name"=>"Относительная влажность, %"
        ),
        array(
            "value"=>"E",
            "name"=>"Парциальное давление водяного пара, гПа"
        ),
        array(
            "value"=>"d",
            "name"=>"Направление ветра, градусы"
        ),
        
        array(
            "value"=>"Pr",
            "name"=>"Осадки, мм"
        ),
        array(
            "value"=>"R",
            "name"=>"Суммарная солнечная радиация, кВт/м^2"
        ),
        array(
            "value"=>"V_mean_10",
            "name"=>"Средняя скорость ветра за 10 секунд"
        ),
        array(
            "value"=>"V_max_10",
            "name"=>"Максимальная скорость ветра за 10 секунд"
        )
    );
    return $options_param;
    }
    //СТАНДАРТНЫЕ ПАРАМЕТРЫ ДЛЯ ВЫБОРОК
    public static function get_standart_for_selects () {
        $options_param=array(
            array(
                "value"=>"N",
                "name"=>"Порядковый номер записи"
            ),
            array(
                "value"=>"Date",
                "name"=>"Дата"
            ),
            array(
                "value"=>"Time",
                "name"=>"Время"
            )
        );
        return $options_param;
    }
    //ИМЯ ПОЛЯ В БД, В ЗАВИСИМОСТИ ОТ ВЫБРАННОГО ПАРАМЕТРА
    public static function get_count_name ($par) {
        $message_exc="несуществующие данные!";
        $counts=array(
            "T"=>"T",
            "P"=>"P",
            "F"=>"F",
            "E"=>"e",
            "d"=>"d",
            "Pr"=>"Pr",
            "R"=>"R",
            "V_mean_10"=>"Vx",
            "V_max_10"=>"Vy",
            "datetime"=>"datetime"
        );
        if (isset($counts["$par"])) {
            return $counts[$par];
        }
        else {
            throw new Exception($message_exc);
        }
    }
    /*
     * ДЛЯ СТАНДАРТНЫХ SELECT'ОВ $param:
     * 1 - НОМЕР МЕСЯЦА,
     * 2 - ДНИ МЕСЯЦА,
     * 3 - ЧАСЫ
     * 4 - МИНУТЫ, СЕКУНДЫ,
     * 5 - ГОД
     */
    public static function get_numbers ($param) {
        //ЧАСТЬ ОБЩАЯ ДЛЯ ВСЕХ, У ДНЕЙ, ЧАСОВ, МИНУТ, СЕКУНД ЕСТЬ ПЕРВЫЕ 10 ЗНАЧЕНИЙ
        if ($param < 5) {
            for ($i=0;$i<10;$i++) {
                $numbers[]="0".$i;
            }
        }
        switch ($param) {
            //НОМЕР МЕСЯЦА
            case 1:
                for ($i=10;$i<13;$i++) {
                    $numbers[]=$i;
                }
            break;
            //ДНИ МЕСЯЦА
            case 2:
                for ($i=10;$i<32;$i++) {
                    $numbers[]=$i;
                }
            break;
            //ЧАСЫ
            case 3:
                for ($i=10;$i<24;$i++) {
                    $numbers[]=$i;
                }
            break;
            //МИНУТЫ ИЛИ СЕКУНДЫ(ЕСЛИ ПОНАДОБЯТСЯ)
            case 4:
                for ($i=10;$i<60;$i++) {
                    $numbers[]=$i;
                }
            break;
            //ГОД, НАЧАЛО С 2000 И ПО 2050
            case 5:
                for ($i=1;$i<52;$i++) {
                    $numbers[]=1999+$i;
                }
            break;
            default:
            break;
        }
        $res = array();
        foreach ($numbers as $val) {
            $dt_n = new DateTime();
            //выбор часового пояса UTC+3 Europe
            $dt_n->setTimezone(new DateTimeZone("Europe/Moscow"));
            //автовыбор текущей даты
            //год
            if ($param == 5) {
                if ($val == $dt_n->format('Y')) {
                    $res[] = "<option value='".$val."' selected='true'>".$val."</option>";
                }
                else {
                    $res[] = "<option value='".$val."'>".$val."</option>";
                }
            }
            //номер месяца
            if ($param == 1) {
                if ($val == $dt_n->format('m')) {
                    $res[] = "<option value='".$val."' selected='true'>".$val."</option>";
                }
                else {
                    if ($val!=="00") { $res[] = "<option value='".$val."'>".$val."</option>";}
                }
            }
            //число
            if ($param == 2) {
                if ($val == $dt_n->format('d')-1) {
                    $res[] = "<option value='".$val."' selected='true'>".$val."</option>";
                }
                else {
                    if ($val!=="00") { $res[] = "<option value='".$val."'>".$val."</option>";}
                }
            }
            //часы
            if ($param == 3) {
                /*
                 * часовой пояс времени и даты записи в таблице UTC+3
                 * на сервере UTC+1
                 */
                if ($val == $dt_n->format('H')) {
                    $res[] = "<option value='".$val."' selected='true'>".$val."</option>";
                }
                else {
                    $res[] = "<option value='".$val."'>".$val."</option>";
                }
            }
            //минуты
            if ($param == 4) {
                if ($val == $dt_n->format('i')) {
                    $res[] = "<option value='".$val."' selected='true'>".$val."</option>";
                }
                else {
                    $res[] = "<option value='".$val."'>".$val."</option>";
                }
            }
        }
        return $res;
    }
    //СТРОКА - В РАЗРАБОТКЕ
    const in_working=
<<<EOT
    <p>
        <b>
            На текущий момент данный раздел сайта находится в разработке
        </b>
    </p>
EOT;
    /*
     * ЗАПРОС МАССИВА МЕТЕОПАРАМЕТРОВ ИЗ БД ДЛЯ ТАБЛИЧНОГО ВЫВОДА И(ИЛИ) excel ФАЙЛА
     * ВХОДНЫЕ ПАРАМЕТРЫ:
     * param_meteo - имя метеопараметра для запроса
     * param_datetime - дата и время начала измерения из БД
     * param_lenght - количество строк запрашиваемого архивного файла в минутах
    */
    public static function get_meteo_array ($param_meteo,$param_datetime,$param_lenght,$param_discretn) {
        if ($param_meteo==="N") {
            return;
        }
        else {
            global $mysqli;
            //ДЛИНА ФАЙЛА В МИНУТАХ
            $lenght=$param_lenght*$param_discretn;
            //ФОРМИРОВАНИЕ ИМЕНИ ИЛИ ПСЕВДОНИМА ДЛЯ ЗАПРОСА ИЗ БД
            $col_name=self::get_count_name($param_meteo);
            /*
             * ТЕСТ ЗАПРОСА ИЗ БД
             * echo "SELECT $col_name FROM `meteo` WHERE `Timestamp` BETWEEN
               '$param_datetime' AND '$param_datetime' +INTERVAL $lenght MINUTE GROUP BY `Timestamp`";
             /* 
             */
            $db_statement = "SELECT $col_name FROM `meteo` WHERE `Timestamp` BETWEEN
                ? AND ? +INTERVAL ? MINUTE GROUP BY `Timestamp`"; //Запрос суммарной солнечной радиации
            //$sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, 973);
            $stmt = $mysqli->prepare($db_statement);
            if (!$stmt) {
                echo "error!";
                /*ДЛЯ ОТЛАДКИ РАСКОММЕНТИРОВАТЬ - ПОЛУЧИТЬ ОШИБКИ
                *
                 print_r($mysqli->error_list);die;
                 /* 
                 */
                die;
            }
            if ($stmt) {
                //БИНДИМ 2 СТРОКОВЫХ ПАРАМЕТРА(s) -- МЕТЕОПАРАМЕТР И ДАТУ НАЧАЛА ИЗМЕРЕНИЯ
                $stmt->bind_param("ssi",$param_datetime,$param_datetime,$lenght);
                $stmt->execute();
                $stmt->bind_result($result_param);
                if ($stmt->field_count) {
                    //ПАРАМЕТР, ДЛЯ СДВИГА НА ОПРЕДЕЛЁННОЕ ЧИСЛО РЕЗУЛЬТИРУЮЩИХ ЗАПИСЕЙ == ДИСКРЕТНОСТЬ АРХИВНОГО ФАЙЛА
                    $k = -$param_discretn;
                    //$param_lenght>$stmt->field_count?$param_lenght=$stmt->field_count:1;
                    $strok_rez=0;
                    for ($i=0;$i<$param_lenght;$i++) {
                        $k = $k + $param_discretn;
                        //перемещаемся на k строк вперёд, с учётом абсолютной(установленной на станции) дискретности измерений
                        $stmt->data_seek($k);
                        //$stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_ABS, $k);
                        $stmt->store_result();
                        $stmt->fetch();
                        //метка конца ряда
                        $label_end=$stmt->fetch();
                        if ($label_end==NULL) break;
                        //ПОСЧИТАЕМ КОЛИЧЕСТВО ЗАПИСЕЙ, КОТОРЫЕ ПОЛУЧИМ В РЕЗУЛЬТАТЕ ЗАПРОСА, НО ТОЛЬКО В ПЕРВОЙ ИТЕРАЦИИ
                        if ($i==0) {$strok_rez=$stmt->num_rows;}
                        $result[]=$result_param;
                    }
                        $stmt->close();
                        return $result;
                }
                else { return "error";die;}
            }
        }
    }
    
}
?>

<?php

namespace PlotsBundle\Controller;
//require_once __DIR__/'../Classes/utilites.php';
use PlotsBundle\Classes\Utilites;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PlotsBundle\Entity\RshuNew;
use PlotsBundle\Entity\RshuRepository;
use RecursiveArrayIterator;
use Exception;
use ErrorException;
use DateTime;
use DateInterval;
//use Symfony\Component\Configuration;
//use Symfony\Component\HttpFoundation\;

class DefaultController extends Controller
{
    //обязательно именовать шаблоны twig, как имя.html.twig
    public function indexAction()
    {
        
        /*ТАК СОСТАВЛЯЕТСЯ ЗАПРОС К БД, С ПОМОЩЬЮ DQL
         * ИЗ КЛАССА UTILITES НЕ ПОЛУЧИЛОСЬ ЕГО СОСТАВИТЬ
         */
        //$em = $this->getDoctrine()->getManager();
        //TODO::
        //$meteo_array = $em->getRepository('PlotsBundle:RshuNew')
            //->get_meteo_array_1 ('T','2015-09-19 00:00:00',3600,900);
        /*
         * Пример использование репозитория
         *
        $meteo_array = $em->getRepository('PlotsBundle:RshuNew')
            ->getF();*/
        
        //$rshu_repository = new RshuRepository();
        //$F = $rshu_repository->_getF();
        //var_dump($meteo_array );
         $em = $this->getDoctrine()->getManager();
        /*стандартные параметры для запроса_дискретность 15 мин, за сутки - отображаются по-умолчанию*/
        //дата_время
        $date = new DateTime();
        $datetime_begin = $date->sub(DateInterval::createFromDateString("1320 minute"));
        /*$datetime_default_view[] =  $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1('datetime', $datetime_begin->format('Y-m-d H:i:s'), 8640, 90);
        $temperature_default_view[] =  $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1('T', $datetime_begin->format('Y-m-d H:i:s'), 8640, 90);
        */
        $utilites = new Utilites();
        //доступ к константе
        $in_working = $utilites::in_working;
        return $this->render('PlotsBundle:Default:base.html.twig',
                array(
                    "adress_name" => $_SERVER['REQUEST_URI'],
                    "in_working"=>$in_working,
                    "array_years"=>$utilites::get_numbers(5),
                    "array_month"=>$utilites::get_numbers(1),
                    "array_days"=>$utilites::get_numbers(2),
                    "array_hours"=>$utilites::get_numbers(3),
                    "array_min_sec"=>$utilites::get_numbers(4),
                    "year_now"=>$utilites::get_datas_for_table("YYYY"),
                    "month_now"=>$utilites::get_datas_for_table("MM"),
                    "day_now"=>$utilites::get_datas_for_table("DD"),
                    "hour_now"=>$utilites::get_datas_for_table("HH"),
                    "selects_params"=>$utilites::get_for_selects (),
                    //"datetime_default_view"=>json_encode($datetime_default_view),
                    //"temperature_default_view"=>json_encode($temperature_default_view)
                    //"R"=>$products,
                )
                );
    }
    
    public function graphAction() {
        if (isset($_POST['params'])) {
            try {
                $m_params_for_query = array();//метеопараметры для запроса
                //ВЫБРАННЫЕ АРХИВНЫЕ МЕТЕОПАРАМЕТРЫ
                $meteo_params=json_decode($_POST['params']);
                $meteo_params=new RecursiveArrayIterator($meteo_params);
                foreach ($meteo_params as $key=>$value) {
                    switch ($value->name) {
                        case "period":
                            $period=$value->value;
                        break;
                        case "discr":
                            $diskretn=$value->value;
                        break;
                        case "year":
                            $year=$value->value;
                        break;
                        case "month":
                            $month=$value->value;
                        break;
                        case "day":
                            $day=$value->value;
                        break;
                        case "time_hh":
                            $time_hh=$value->value;
                        break;
                        case "time_mm":
                            $time_mm=$value->value;
                        break;
                        case "meteo_param":
                            $m_params_for_query[]=$value->value;
                        break;
                        default:
                        break;
                    }
                }
                /*
                 * имя файла для экспорта графика
                 * формат:
                 * метеопараметр1метеопараметр2метеопараметрN_ГГГГММДД_ЧЧММ_ДЛИТЕЛЬНОСТЬ_ДИСКРЕТНОСТЬ
                 */
                $filename_for_export = "";
                foreach ($m_params_for_query as $val) {
                    $filename_for_export.=$val; 
                }
                $filename_for_export = $filename_for_export."_".$year.$month.$day."_".$time_hh.$time_mm;
                $filename_for_export = $filename_for_export."_".$period."_".$diskretn;
                //запишем отдельно дату и время для запроса (чтобы построить график)
                $m_params_for_query[] = "datetime";
                
                if ($diskretn >$period) {
                    return new Response(0x02);
                }
                
                if (count($m_params_for_query)===8) {
                    return new Response(0x01);
                }
                
                $datetime = $year."-".$month."-".$day." ".$time_hh.":".$time_mm.":00";
                
                $datetime_from_title = $datetime;//дата и время начала измерений
                $datetime_to_title = new DateTime($datetime);
                $period_sec = $period*10;
                $datetime_to_title->add(new DateInterval('PT'.$period_sec.'S'));//дата и время окончания измерений
                
                $meteo_result=array();//echo 1;die;
                $em = $this->getDoctrine()->getManager();
                //для парциального давления
                $E=0;
                //ЗАПИШЕМ ВСЕ ЗАПРАШИВАЕМЫЕ АРХИВНЫЕ ДАННЫЕ В МАССИВ
                foreach ($m_params_for_query as $meteo_name) {
                    //для всех, кроме E, которое считаем "на лету"
                    if ($meteo_name!=="E") { 
                        $meteo_result[$meteo_name][] = $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1($meteo_name, $datetime, $period, $diskretn);
                    }
                    if ($meteo_name=="E") {
                        $E=1;
                    }
                };
                //рассчет E TODO::сделать, чтобы E рассчитывался только при выборе T и F
                if ($E==1) {
                    if (!isset($meteo_result["T"])) {
                        $meteo_result["T"][] = $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1("T", $datetime, $period, $diskretn);
                    }
                    if (!isset($meteo_result["F"])) {
                        $meteo_result["F"][] = $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1("F", $datetime, $period, $diskretn);
                    }
                    foreach ($meteo_result["T"][0] as $key=>$val) {
                        $meteo_result["E"][0][] = ROUND(6.103807*($meteo_result["F"][0][$key]/100)*pow(10,((7.63 * $val)/(241.9+$val))),2);
                    }
                    if (!in_array("F",$m_params_for_query)) {
                        unset($meteo_result["F"]);
                    }
                    if (!in_array("T",$m_params_for_query)) {
                        unset($meteo_result["T"]);
                    }
                }
                //переведем дату в формат jquery flot
                $dt = array();
                foreach ($meteo_result['datetime'][0] as $key=>$value) {
                    $dt[] = strtotime($value)*1000+1000*60*60;
                }
                unset($meteo_result['datetime']);
                $meteo_result['datetime'][0] = $dt;
                $meteo_result["filename_for_export"][0] = $filename_for_export;
                $meteo_result["time_to"][0] = $datetime_to_title->format('Y-m-d H:i:s');
                $meteo_result["time_from"][0] = $datetime_from_title;
                if (empty($dt)) {
                    return new Response("null");
                }
                else {
                    return new Response(json_encode($meteo_result));
                }
            }
            catch (ErrorException $e) {
                return new Response($e);
            }
        }
        else {
            return new Response();
        }
    }
    
    /*
     * проверка плагина для построения графиков - highcharts
     */
    //обязательно именовать шаблоны twig, как имя.html.twig
    public function hightcharts_testAction()
    {
        
        /*ТАК СОСТАВЛЯЕТСЯ ЗАПРОС К БД, С ПОМОЩЬЮ DQL
         * ИЗ КЛАССА UTILITES НЕ ПОЛУЧИЛОСЬ ЕГО СОСТАВИТЬ
         */
        //$em = $this->getDoctrine()->getManager();
        //TODO::
        //$meteo_array = $em->getRepository('PlotsBundle:RshuNew')
            //->get_meteo_array_1 ('T','2015-09-19 00:00:00',3600,900);
        /*
         * Пример использование репозитория
         *
        $meteo_array = $em->getRepository('PlotsBundle:RshuNew')
            ->getF();*/
        
        //$rshu_repository = new RshuRepository();
        //$F = $rshu_repository->_getF();
        //var_dump($meteo_array );
         $em = $this->getDoctrine()->getManager();
        /*стандартные параметры для запроса_дискретность 15 мин, за сутки - отображаются по-умолчанию*/
        //дата_время
        $date = new DateTime();
        $datetime_begin = $date->sub(DateInterval::createFromDateString("1320 minute"));
        /*$datetime_default_view[] =  $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1('datetime', $datetime_begin->format('Y-m-d H:i:s'), 8640, 90);
        $temperature_default_view[] =  $em->getRepository('PlotsBundle:RshuNew')
                            ->get_meteo_array_1('T', $datetime_begin->format('Y-m-d H:i:s'), 8640, 90);
        */
        $utilites = new Utilites();
        //доступ к константе
        $in_working = $utilites::in_working;
        return $this->render('PlotsBundle:Default:base_highcharts.html.twig',
                array(
                    "adress_name" => "/previously_analysys_rshu/web/app_dev.php/",//для локальной версиии
                    "in_working"=>$in_working,
                    "array_years"=>$utilites::get_numbers(5),
                    "array_month"=>$utilites::get_numbers(1),
                    "array_days"=>$utilites::get_numbers(2),
                    "array_hours"=>$utilites::get_numbers(3),
                    "array_min_sec"=>$utilites::get_numbers(4),
                    "year_now"=>$utilites::get_datas_for_table("YYYY"),
                    "month_now"=>$utilites::get_datas_for_table("MM"),
                    "day_now"=>$utilites::get_datas_for_table("DD"),
                    "hour_now"=>$utilites::get_datas_for_table("HH"),
                    "selects_params"=>$utilites::get_for_selects (),
                    //"datetime_default_view"=>json_encode($datetime_default_view),
                    //"temperature_default_view"=>json_encode($temperature_default_view)
                    //"R"=>$products,
                )
                );
    }
}

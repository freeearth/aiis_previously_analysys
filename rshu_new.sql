-- phpMyAdmin SQL Dump
-- version 4.3.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 21 2016 г., 23:52
-- Версия сервера: 5.5.44-0+deb8u1
-- Версия PHP: 5.6.7-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `vaisala`
--

-- --------------------------------------------------------

--
-- Структура таблицы `rshu_new`
--

CREATE TABLE IF NOT EXISTS `rshu_new` (
  `id` int(30) NOT NULL COMMENT 'номер очередной записи',
  `T` double DEFAULT NULL COMMENT 'температура воздуха, К',
  `P` double DEFAULT NULL COMMENT 'атмосферное давление, Па',
  `F` double DEFAULT NULL COMMENT 'Относительная влажность',
  `Pr` double DEFAULT NULL COMMENT 'осадки,мм',
  `R` double DEFAULT NULL COMMENT 'суммарная солнечная радиация кВт/м^2',
  `Vx` double DEFAULT NULL COMMENT 'Средняя скорость ветра за 10 секунд',
  `Vy` double DEFAULT NULL COMMENT 'Максимальная скорость ветра за 10 секунд',
  `d` double DEFAULT NULL COMMENT 'направление ветра, градусы',
  `datetime` datetime DEFAULT NULL COMMENT 'время очередного измерения',
  `edited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'время добавления данных'
) ENGINE=MyISAM AUTO_INCREMENT=20242839 DEFAULT CHARSET=utf8 COMMENT='таблица с данными на станции РГГМУ';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `rshu_new`
--
ALTER TABLE `rshu_new`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `rshu_new`
--
ALTER TABLE `rshu_new`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT COMMENT 'номер очередной записи',AUTO_INCREMENT=20242839;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

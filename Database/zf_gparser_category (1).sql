-- phpMyAdmin SQL Dump
-- version 4.4.15.5
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 17 2016 г., 13:56
-- Версия сервера: 5.5.48
-- Версия PHP: 5.5.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `zf_arize`
--

-- --------------------------------------------------------

--
-- Структура таблицы `zf_gparser_category`
--

CREATE TABLE IF NOT EXISTS `zf_gparser_category` (
  `id_gparser_category` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_external_id` bigint(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `zf_gparser_category`
--

INSERT INTO `zf_gparser_category` (`id_gparser_category`, `category_name`, `category_external_id`) VALUES
(1, 'Аварийные / справочные / экстренные службы', 2955607514551595),
(2, 'Автосервис / Автотовары', 2955607514589079),
(3, 'Город / Власть', 2955607514546177),
(4, 'Досуг / Развлечения / Общественное питание', 2955607514546178),
(5, 'Интернет / Связь / Информационные технологии', 2955607514565708),
(6, 'Коммунальные / бытовые / ритуальные услуги', 2955607514546186),
(7, 'Компьютеры / Бытовая техника / Офисная техника', 2955607514546179),
(8, 'Культура / Искусство / Религия', 2955607514546180),
(9, 'Мебель / Материалы / Фурнитура', 2955607514546963),
(10, 'Медицина / Здоровье / Красота', 2955607514546181),
(11, 'Металлы / Топливо / Химия', 2955607514546189),
(12, 'Оборудование / Инструмент', 2955607514546191),
(13, 'Образование / Работа / Карьера', 2955607514546182),
(14, 'Одежда / Обувь', 2955607514547211),
(15, 'Охрана / Безопасность', 2955607514547797),
(16, 'Продукты питания / Напитки', 2955607514546190),
(17, 'Реклама / Полиграфия / СМИ', 2955607514546183),
(18, 'Спорт / Отдых / Туризм', 2955607514546184),
(19, 'Строительные / отделочные материалы', 2955607514589043),
(20, 'Строительство / Недвижимость / Ремонт', 2955607514546185),
(21, 'Текстиль / Предметы интерьера', 2955607514589048),
(22, 'Товары для животных / Ветеринария', 2955607514546922),
(23, 'Торговые комплексы / Спецмагазины', 2955607514552723),
(24, 'Транспорт / Грузоперевозки', 2955607514546188),
(25, 'Хозтовары / Канцелярия / Упаковка', 2955607514546187),
(26, 'Электроника / Электротехника', 2955607514589039),
(27, 'Юридические / финансовые / бизнес-услуги', 2955607514547145);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `zf_gparser_category`
--
ALTER TABLE `zf_gparser_category`
  ADD PRIMARY KEY (`id_gparser_category`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `zf_gparser_category`
--
ALTER TABLE `zf_gparser_category`
  MODIFY `id_gparser_category` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

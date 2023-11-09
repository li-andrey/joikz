CREATE TABLE IF NOT EXISTS `i_guest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_section` int(11) DEFAULT '0',
  `version` varchar(20) DEFAULT NULL,
  `url` text NOT NULL,
  `name` text,
  `info` longtext,
  `active` tinyint(4) DEFAULT NULL,
  `id_sort` int(11) DEFAULT NULL,
  `image` text,
  `data` datetime DEFAULT NULL,
  `mail` varchar(250) DEFAULT NULL,
  `guest` longtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DELETE FROM `i_option` where category = 'guest';
DELETE FROM `i_params` where category = 'guest';

INSERT INTO `i_option` (`category`, `category_id`, `required_fields`, `select_fields`, `id_sort`, `name_ru`, `name_en`, `type_field`, `width`, `format_date`, `height`, `select_elements`, `size_file`, `format_file`, `type_resize`, `w_resize_small`, `h_resize_small`, `watermark`, `w_resize_big`, `h_resize_big`, `filter`, `search`, `translit`) VALUES
('guest', 0, 0, 1, 10, 'Активность', 'active', 'i7', 0, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('guest', 0, 1, 1, 20, 'Имя', 'name', 'i1', 30, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('guest', 0, 0, 1, 30, 'Дата', 'data', 'i2', 30, 'Y-m-d H:i:s', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('guest', 0, 0, 1, 40, 'E-mail', 'mail', 'i1', 30, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('guest', 0, 1, 0, 50, 'Отзыв', 'guest', 'i6', 30, '', 5, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('guest', 0, 0, 0, 50, 'Картинка', 'image', 'i11', 30, '', 5, '', '30000000', 'jpg|gif|png|jpeg|swf|JPG|JPEG|GIF|PNG|SWF', 'crop', 150, 150, '', 300, 300, 0, 0, 0);

INSERT INTO `i_params` (`id_block`, `name`, `type`, `value`, `name_en`, `version`, `category`) VALUES
(0, 'E-mail', 'i1', '', 'email', 'all', 'guest'),
(0, 'Заголовок', 'i1', 'Отзывы', 'title', 'all', 'guest'),
(0, 'Комментарии', 'i1', '1', 'comments', 'all', 'guest'),
(0, 'На страницу', 'i1', '6', 'per_page', 'all', 'guest'),
(0, 'Описание', 'i1', 'Отзывы сайта', 'descr', 'all', 'guest'),
(0, 'Ключевые слова', 'i1', 'Ключ 1', 'keyw', 'all', 'guest');
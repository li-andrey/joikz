INSERT INTO `i_option` (`category`, `category_id`, `required_fields`, `select_fields`, `id_sort`, `name_ru`, `name_en`, `type_field`, `width`, `format_date`, `height`, `select_elements`, `size_file`, `format_file`, `type_resize`, `w_resize_small`, `h_resize_small`, `watermark`, `w_resize_big`, `h_resize_big`, `filter`, `search`, `translit`) VALUES
('block_elements', 2, 0, 1, 10, 'Активность', 'active', 'i7', 0, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('block_elements', 2, 0, 1, 20, 'Сортировка', 'id_sort', 'i5', 30, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('block_elements', 2, 1, 1, 30, 'Название', 'name', 'i1', 30, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('block_elements', 2, 0, 1, 40, 'Ссылка', 'link', 'i1', 30, '', 0, '', '', '', 'auto', 200, 200, '', 600, 600, 0, 0, 0),
('block_elements', 2, 0, 1, 50, 'Баннер', 'image', 'i11', 0, '', 0, '', '3000000', 'jpg|gif|png|jpeg|swf|JPG|JPEG|GIF|PNG|SWF', 'exact', 100, 50, '', 1920, 720, 0, 0, 0);

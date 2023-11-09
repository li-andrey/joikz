<?
$this->meta(
    array(
        'h1' => $this->t('SearchTitle'),
        'title' => $this->t('SearchTitle'),
        'descr' => '',
        'keyw' => '',
    )
);
$this->header();
?>
    <div class="section-full mobile-page-padding p-t80 p-b80 bg-gray">
    <div class="container">
        <div class="section-content">
            <div class="row">
                <div class="col-md-12 ">
                    <?
                    $modules = array(
                        array(
                            'active' => 1,
                            'name' => 'Текстовые страницы',
                            'id_section' => '',
                            'url' => '',
                            'table' => 'i_page',
                            'table_e' => '',
                            'fields' => array(
                                'name',
                                'anounce',
                                'info',
                            )
                        ),
                        array(
                            'active' => 1,
                            'name' => 'Услуги',
                            'id_section' => '',
                            'url' => 'catalog/',
                            'table' => 'i_cat',
                            'table_e' => 'i_cat_elements',
                            'fields' => array(
                                'name',
                                'anounce',
                                'text',
                            ),
                            'fields_e' => array(
                                'name',
                                'anounce',
                                'text',
                            )
                        ),
                    
                    );
                    
                    $onepage = 10;//сколько результатов поиска отображать на странице
                    $rows = 3;//во сколько колонок выводить чекбоксы формы поиска
                    //заносим где искать, #SEARCH# служебный маркер, который соответствует введённому в запрос слову
                    $where = array();
                    foreach ($modules as $v) {
                        if ($v["active"] == 1) {
                            if ($v['table'] != '') {
                                $arr_field = array();
                                foreach ($v["fields"] as $f) {
                                    
                                    $arr_field[] = " (INSTR(`" . $f . "`, '#SEARCH#')) ";
                                    
                                }
                                
                                
                                if (sizeof($arr_field) > 0) {
                                    
                                    $where[$v["url"] . "_b"] = array('title' => $v["name"], 'sql' => "select * from " . $v["table"] . " where " . ($v["id_section"] != '' ? " id_section=" . $v["id_section"] . " and " : "") . " version='$this->lang' and (" . join(" or ", $arr_field) . ")");
                                    
                                }
                                
                            }
                            
                            if ($v['table_e'] != '') {
                                
                                $arr_field = array();
                                
                                foreach ($v["fields_e"] as $f) {
                                    
                                    $arr_field[] = " (INSTR(`" . $f . "`, '#SEARCH#')) ";
                                    
                                }
                                
                                if (sizeof($arr_field) > 0) {
                                    
                                    $where[$v["url"] . "_e"] = array('title' => $v["name"], 'sql' => "select * from " . $v["table_e"] . " where " . ($v["id_section"] != '' ? " (id_section=" . $v["id_section"] . " or id_section in (select id from " . $v["table_e"] . " where id_section=" . $v["id_section"] . ")) and " : "") . " version='$this->lang' and (" . join(" or ", $arr_field) . ")");
                                    
                                }
                                
                            }
                            
                        }
                        
                    }
                    
                    //vd($_REQUEST);
                    
                    $request = array();
                    
                    if (is_array(@$_REQUEST['where'])) {
                        
                        foreach ($_REQUEST['where'] as $wer) {
                            
                            $request[] = $wer;
                            
                            if (isset($where[str_replace('_b', '_e', $wer)])) {
                                
                                $request[] = str_replace('_b', '_e', $wer);
                                
                            }
                            
                        }
                        
                    } else {
                        
                        foreach ($where as $k => $wer) {
                            
                            $request[] = $k;
                            
                            if (isset($where[str_replace('_b', '_e', $k)])) {
                                
                                $request[] = str_replace('_b', '_e', $k);
                                
                            }
                            
                        }
                        
                    }
                    
                    $request = array_unique($request);
                    
                    
                    //vd($request);
                    
                    error_reporting(E_ALL);
                    
                    ini_set("display_errors", "on");
                    
                    #ПЕРЕМЕННЫЕ.end
                    
                    
                    #MAIN-PART
                    
                    $search = @$ob->pr(trim($_REQUEST['search']));
                    
                    $out = array();
                    
                    if ($search != '') {
                        
                        $pg = 0;
                        
                        if (!empty($_REQUEST['pg'])) $pg = intval($_REQUEST['pg']) - 1;
                        
                        if (empty($_REQUEST['where'])) $_REQUEST['where'] = array_keys($where);
                        
                        $result = array();
                        
                        if (is_array($_REQUEST['where'])) {
                            
                            $index = 0;
                            
                            $count = array();
                            
                            $parsed = array();
                            
                            $start = $pg * $onepage;
                            
                            $total_count = 0;
                            
                            foreach ($request as $w) {
                                
                                if (!isset($where[$w])) continue;
                                
                                $where[$w]['sql'] = str_replace('#SEARCH#', $search, $where[$w]['sql']);
                                
                                if (!preg_match("/^SELECT(.+?)FROM\s+([\w`]+)(\s+WHERE(.+))?$/i", $where[$w]['sql'], $ok)) continue;
                                
                                $parsed[$w] = array('what' => $ok[1], 'table' => $ok[2], 'where' => $ok[3] ? $ok[3] : '');
                                
                                $total_count += $count[$w] = A::$app->counts($parsed[$w]['table'], $parsed[$w]['where']);
                                
                                
                                $r = A::$db->query($where[$w]['sql'] . " LIMIT " . $start . ", " . ($onepage - $index));
                                
                                //echo  $where[$w]['sql']." LIMIT ".$start.", ".($onepage-$index);
                                
                                
                                if ($index < $onepage && $start >= 0 && $r->num_rows > 0) {
                                    
                                    while ($e = $r->fetch_object()) {
                                        
                                        //echo $w.'<br>';
                                        
                                        $result[] = array(
                                            
                                            'title' => $where[$w]['title'],
                                            
                                            'link' => '<a target="_blank" href="' . A::$app->link() . '' . (str_replace(array('_e', '_b'), '', $w)) . '' . $e->url . '' . (strstr($w, '_b') && $w != '/page/_b' ? '/' : '/') . '">' . mb_substr(strip_tags($e->name), 0, 50, "UTF-8") . '</a>',
                                            
                                            'text' => (@$e->text != '' ? mb_substr(strip_tags($e->text), 0, 200, "UTF-8") : mb_substr(strip_tags(@$e->info), 0, 200, "UTF-8"))
                                        
                                        );
                                        
                                        
                                        $index = count($result);
                                        
                                    }
                                    
                                    $start = 0;
                                    
                                } else $start -= $count[$w];
                                
                            }
                            
                        }

#MAIN-PART.end


#HTML-PART -> RESULT; HTML РЕЗУЛЬТАТА ЗАПРОСА НАЧИНАЕТ ВЫВОДИТЬСЯ ТУТ
                        
                        #ЕСЛИ РЕЗУЛЬТАТ НЕ ПУСТ, НАЧИНАЕМ ФОРМИРОВАТЬ HTML РЕЗУЛТАТА ПОИСКА
                        
                        if ($result) {
                            
                            $out[] = '<h4>Всего найдено: ' . $total_count . ', отображено ' . ($pg * $onepage + 1) . '&ndash;' . ($pg * $onepage + count($result)) . '</h4><br>';
                            
                            $pages = drawPages($total_count, $onepage, $pg, array("search=" . $search, (@$_REQUEST['where'] ? 'where[]=' . join("&where[]=", $_REQUEST['where']) : '')));
                            
                            $out[] = $pages;
                            
                            
                            $title = null;
                            
                            #ПЕРЕБОР РЕЗУЛЬТАТОВ
                            
                            foreach ($result as $r) {
                                
                                if ($r['title'] != $title) {
                                    
                                    $index = 0;
                                    
                                    $title = $r['title'];
                                    
                                    $out[] = '<br><h4>' . $title . '</h4><br>';
                                    
                                }
                                
                                $out[] = '<div>' . (++$index) . '. ' . $r['link'] . '</div>';
                                
                                $out[] = '<div class="hint" style="padding-left:17px;">' . $r['text'] . '</div><br>';
                                
                            }
                            
                            $out[] = $pages;
                            
                        } else $out[] = 'По вашему запросу ничего не найдено.';

#HTML-PART.end;
                    
                    } else $out[] = 'Введите параметры запроса.';
                    
                    
                    #HTML-PART -> FINAL-PART; ТУТ ПРОИСХОДИТ ФОРМИРОВАНИЕ САМОЙ ФОРМЫ ПОИСКА, КЛЕЯТСЯ РЕЗУЛЬТАТЫ ЗАПРОСА ПОИСКА И ПРОИСХОДИТ ОТОБРАЖЕНИЕ.
                    
                    $inc = 0;
                    
                    $index = 0;
                    
                    $temp = array('<table cellpadding="3" style="width:auto; border:0px; margin-top:10px">');
                    
                    $show = array();
                    
                    foreach ($where as $k => $v) {
                        
                        if (!in_array($v["title"], $show)) {
                            
                            if (!$index) $temp[] = '<tr>';
                            
                            $temp[] = '<td style="padding-right:20px; border:0px;"><input style="display:inline-block" type="checkbox" name="where[]" value="' . $k . '"' . (@in_array($k, $_REQUEST['where']) ? ' checked' : '') . '>&nbsp;&nbsp;' . $v['title'] . '</td>';
                            
                            
                            if (++$index >= $rows) {
                                
                                $temp[] = '</tr>';
                                
                                $index = 0;
                                
                            }
                            
                            $show[] = $v["title"];
                            
                            $inc++;
                            
                        }
                        
                    }
                    
                    if ($index) $temp[] = ($inc > $rows ? str_repeat('<td></td>', $rows - $index) : '') . '</tr>';
                    
                    $temp[] = '</table>';
                    
                    
                    $content = array('<div>', '<form method="post" action="' . A::$app->path() . '">');
                    
                    $content[] = '<input type="text" id="searchf" name="search" class="form-control" style="width:300px; display:inline-block;" value="' . $search . '"> <input type="submit" class="btn" style="    margin-top: -6px;
    padding: 9px 29px;
    float: none;
    vertical-align: middle;
    float: none;
    margin-left: 20px;" value="Найти">';
                    
                    $content[] = join("\r\n", $temp);
                    
                    $content[] = '</form>';
                    
                    $content[] = '</div><br>';
                    
                    $content[] = join("\r\n", $out);
                    echo join("\n", $content);
                    ?>
                </div>
            </div>
        </div>
    </div>
    </div>

<? $this->footer(); ?>
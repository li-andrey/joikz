
</section>
<!-- Begin: Page Footer-->
<footer id="content-footer" class="affix">
  <div class="row">
    <div class="col-xs-11">
      <p style="font-size: 10px; color: #333; text-align: center;">Все права защищены. Любое изменение или копирование преследуется законом РК об авторских правах.  <?='Сгенерировано за: '.round(microtime(1)-$startTimer, 3).' сек.';  ?></p>
    
</div>
<div class="col-xs-1 text-right"><a href="#content" class="footer-return-top"><span class="fa fa-angle-up"></span></a></div>
</div>
</footer>
</section>
</div>

<script type="text/javascript">
    $(function(){
        $($('.panel-tabs li.active a').attr("href")).addClass("active")
    })
</script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        "use strict";
        Demo.init();
        Core.init();
    });
    function open_menu(id,panel_id){ 
        var div = document.getElementById(id);
        if(div.style.display=='none'){
            div.style.display='table';
            if(panel_id=='panel1'){
                document.getElementById('panel1').style.height='235px';
            }else{
                document.getElementById('panel0').style.height='auto';
            }
        }else{
            div.style.display='none';
        }
    }



    function SectionClick(id){  
        var div = document.getElementById(id);
        if(div.style.display=='none'){
            div.style.display='block';
        }else{
            div.style.display='none';
        }
    }

    function MM_jumpMenu(targ,selObj,restore){ 
        eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
        if (restore) selObj.selectedIndex=0;
    }

    function exit_to(){
        if (confirm("Вы действительно хотите выйти?")){
            document.location.href='/admin/modules/desktop.php?exit=true';
        }
    }

    function check(i){
        thisCheckbox = document.getElementById(i);
        thisCheckbox.checked = !thisCheckbox.checked;
    }

    function screen_width(){
        save_ob=document.getElementById('save_title');
        if(save_ob){
            save_ob.style.left=screen.width/2;
            save_ob.style.top=screen.height/2-80;
        }
    }

    $(function(){
        $('.add_more span').click(function(){
            var row = $('.add_more>div').clone();
            $('.col-md-1', row).html('<a href="javascript:;" class="text-danger"><span class="glyphicon glyphicon-remove-circle" style="font-size: 28px; margin-top: 8px;"></span></a>');
            $('.add_more').after(row);
            $('.add_more input').val("");
        })

        $('.add_moref span').click(function(){
            var row = $('.add_moref>div').clone();
            $('.col-md-1', row).html('<a href="javascript:;" class="text-danger"><span class="glyphicon glyphicon-remove-circle" style="font-size: 28px; margin-top: 8px;"></span></a>');
            $('.add_moref').after(row);
            $('.add_moref input').val("");
        })


         $('.panel').on('click', 'a.text-danger',function(){
            $(this).parent().parent().remove();
         })
         <?
         $url = $_SERVER["PHP_SELF"];
         $uri = explode('/', $url);
         unset($uri[sizeof($uri)-1]);
         $url = join('/', $uri).'/';
         ?>
         $('.sub-nav a[href="<?=$url?>"]').parent().parent().prev().addClass("menu-open");
    })
</script>
</body>
</html>
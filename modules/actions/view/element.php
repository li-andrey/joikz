<h1 class="page-title"><?=$e["name"]?></h1>
<div class="page-content">
    <div class="article-content">
    <?=$e["text"]?>
    </div>
    <span class="post-date"><?=A::$api->date(LANG, $e["data"], 'sql', 'datetext')?></span>
    <div class="post-social">
        <?
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        ?>
        <span>Расскажите друзьям</span>
        <ul class="social">
            <li>
                <a href="https://www.facebook.com/sharer.php?src=sp&amp;u=<?=$actual_link?>&amp;picture=&amp;title=&amp;utm_source=share2" target="_blank" onclick="window.open(this.href, document.title, 'width=800,height=600,left=' + (screen.availWidth - 800)/2 + ',top=' + (screen.availHeight - 600)/2); return false;">
                    <img src="<?=ASSETS?>images/fb.png" alt="" class="fb-icon">
                </a>
            </li>
            <li>
                <a href="https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&amp;st.shareUrl=<?=$actual_link?>&amp;utm_source=share2" target="_blank" onclick="window.open(this.href, document.title, 'width=800,height=600,left=' + (screen.availWidth - 800)/2 + ',top=' + (screen.availHeight - 600)/2); return false;">
                    <img src="<?=ASSETS?>images/ok.png" alt="" class="ok-icon">
                </a>
            </li>
            <li>
                <a href="https://vk.com/share.php?url=<?=$actual_link?>&amp;utm_source=share2" target="_blank" onclick="window.open(this.href, document.title, 'width=800,height=600,left=' + (screen.availWidth - 800)/2 + ',top=' + (screen.availHeight - 600)/2); return false;">
                    <img src="<?=ASSETS?>images/vk.png" alt="" class="vk-icon">
                </a>
            </li>
        </ul>        </div>
</div>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $this->title ?></title>
    <meta name="description" content="<?= $this->description ?>">
    <meta name="keywords" content="<?= $this->keywords ?>">
    <? A::$app->css(); ?>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
       (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
       m[i].l=1*new Date();
       for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
       k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
       (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

       ym(95381960, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            ecommerce:"dataContainer"
       });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/95381960" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R3GX120HS9"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-R3GX120HS9');
    </script>
        <!-- Google tag (gtag.js) -->
</head>

<body class="footer-fixed">
    <header>
        <nav class="navbar navbar-expand-lg  fixed-top">
            <div class="container">
                <a class="navbar-brand" href="<?= LINK ?>" style="background: url(<?= A::$app->wf(1, ["f" => 'image']) ?>) no-repeat center center; ">
                    <img src="<?= A::$app->wf(1, ["f" => 'image']) ?>" height="30" align="Joi" style="visibility:hidden">
                </a>
                <div class="icons d-block d-lg-none">
                    <div class="n-search float-left relative d-none d-md-block">
                        <div class="n-search__top jsSearchTop">
                            <div class="n-search__inner">
                                <!-- search form -->
                                <form class="n-search__form" name="search_form" action="<?= LINK ?>catalog/">
                                    <input class="n-search__input font--en font--14 font--md jsHeaderSearch form-control mr-sm-2" type="text" placeholder="Поиск" name="search" data-validate="required" autocomplete="off">
                                </form>
                                <!--// search form -->
                            </div>
                        </div>
                        <span class="n-search-popup-close close-btn jsSearchClose"></span>
                    </div>
                    <ul>
                        <li>
                            <a href="tel:<?= A::$app->wf(2, ["f" => 'text', 'r' => 'p']) ?>" class="phone">
                                <img src="/upload/phone.png" />
                            </a>
                        </li>
                        <? if (!A::$app->user->auth()) { ?>
                                <li>
                                    <a href="<?= LINK ?>user/login/" class="person">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                        </svg>

                                    </a>
                                </li>
                            <? } else { ?>
                                <li>

                                    <div class="dropdown">
                                        <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="person">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuButton">
                                            <span class="dropdown-menu-arrow"></span>
                                            <div class="nav-item">
                                                <a class="nav-link text-nowrap" href="<?= LINK ?>user/account/">Мой профиль</a>
                                            </div>
                                            <div class="nav-item">
                                                <a class="nav-link text-nowrap" href="<?= LINK ?>user/history/">История заказов</a>
                                            </div>
                                            <div class="nav-item">
                                                <a class="nav-link text-nowrap" href="<?= LINK ?>logout/">Выход</a>
                                            </div>
                                        </div>
                                    </div>


                                </li>
                            <? } ?>
                        <li>
                            <a href="<?= LINK ?>user/wishlist/" class="wishlist">
                                <img src="/upload/favorites.png" />
                                <span class="count"><?= A::$app->wishlist->count() ?></span>
                            </a>
                        </li>
                        <li class="cart">
                            <a href="<?= LINK ?>shop/cart/" class="cart">
                                <img src="/upload/cart.png" />
                                <span class="count"><?= A::$app->cart->count() ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                    <ul class="navbar-nav ml-auto mr-auto">
                        <? foreach (A::$app->catalog->bb(1) as $key => $r) { ?>
                            <? $res = A::$app->catalog->categories($r["id"]); ?>

                            <li class="nav-item">
                                <a class="nav-link" href="<?= LINK ?>catalog/<?= $r["url"] ?>/"><?= $r["name"] ?></a>
                                <? if (sizeof($res) > 0) { ?>
                                    <span class="arrow"><i class="fa fa-angle-down"></i></span>
                                    <ul>
                                        <? foreach ($res as $key => $r1) { ?>
                                            <li><a href="<?= LINK ?>catalog/<?= $r1["url"] ?>/"><?= $r1["name"] ?></a></li>
                                        <? } ?>
                                    </ul>
                                <? } ?>
                            </li>
                        <? } ?>
                    </ul>

                    <div class="n-search float-left relative d-none d-lg-block">
                        <div class="n-search__top jsSearchTop">
                            <div class="n-search__inner">
                                <!-- search form -->
                                <form class="n-search__form" name="search_form" action="<?= LINK ?>catalog/">
                                    <input class="n-search__input font--en font--14 font--md jsHeaderSearch form-control mr-sm-2" type="text" placeholder="Поиск" name="search" data-validate="required" autocomplete="off">
                                </form>
                                <!--// search form -->
                            </div>
                        </div>
                        <span class="n-search-popup-close close-btn jsSearchClose"></span>
                    </div>
                    <div class="icons d-none d-lg-block">
                        <ul>
                            <li>
                                <div class="dropdown">
                                    <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="phone">
                                        <img src="/upload/telephone.svg" height="28" />
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuButton">
                                        <span class="dropdown-menu-arrow"></span>
                                        <div class="nav-item">
                                            <a class="nav-link text-nowrap" href="tel:<?= A::$app->wf(2, ["f" => 'text', 'r' => 'p']) ?>">
                                                <?= A::$app->wf(2, ["f" => 'text', 'r' => 'p']) ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <? if (!A::$app->user->auth()) { ?>
                                <li>
                                    <a href="<?= LINK ?>user/login/" class="person">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                        </svg>

                                    </a>
                                </li>
                            <? } else { ?>
                                <li>

                                    <div class="dropdown">
                                        <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="person">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                            </svg>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuButton">
                                            <span class="dropdown-menu-arrow"></span>
                                            <div class="nav-item">
                                                <a class="nav-link text-nowrap" href="<?= LINK ?>user/account/">Мой профиль</a>
                                            </div>
                                            <div class="nav-item">
                                                <a class="nav-link text-nowrap" href="<?= LINK ?>user/history/">История заказов</a>
                                            </div>
                                            <div class="nav-item">
                                                <a class="nav-link text-nowrap" href="<?= LINK ?>logout/">Выход</a>
                                            </div>
                                        </div>
                                    </div>


                                </li>
                            <? } ?>
                            <li>
                                <a href="<?= LINK ?>user/wishlist/" class="wishlist">
                                    <img src="/upload/bookmark.svg" height="28" />
                                    <span class="count"><?= A::$app->wishlist->count() ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="<?= LINK ?>shop/cart/" class="cart">
                                    <img src="/upload/bag.svg" height="28" />
                                    <span class="count"><?= A::$app->cart->count() ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
</main>

<footer>

    <ul class="menu">
        <!--<li>
            <a href="">
                <span><?= A::$app->wf(17, ["f" => 'name']) ?></span>
            </a>
            <div class="info">
                <? if (!A::$app->user->auth()) { ?>
                    <?= A::$app->wf(17, ["f" => 'text']) ?>
                <? } ?>

                <p class="text-center">
                    <? if (!A::$app->user->auth()) { ?>
                        <a class="btn btn-outline-primary btn-lg" href="<?= LINK ?>user/login/">Авторизоваться</a>
                    <? } else { ?>
                        <a class="btn btn-outline-primary btn-lg" href="<?= LINK ?>user/account/">Мой профиль</a>
                        <a class="btn btn-outline-primary btn-lg" href="<?= LINK ?>user/history/">История заказов</a>
                        <a class="btn btn-outline-primary btn-lg" href="<?= LINK ?>logout/">Выход</a>
                    <? } ?>
                </p>
            </div>
        </li>-->
        <li>
            <a href="">
                <span><?= A::$app->wf(18, ["f" => 'name']) ?></span>
            </a>
            <div class="info">
                <?= A::$app->wf(18, ["f" => 'text']) ?>
            </div>
        </li>
        <li>
            <a href="">
                <span><?= A::$app->wf(19, ["f" => 'name']) ?></span>
            </a>
            <div class="info">
                <?= A::$app->wf(19, ["f" => 'text']) ?>
            </div>
        </li>
    </ul>
    <div class="container py-4">
        <?= A::$app->wf(20, ["f" => 'text']) ?>
    </div>
</footer>
<?
A::$app->script();
?>
</body>

</html>
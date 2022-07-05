<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?= $head; ?>

    <link rel="stylesheet" href="<?= theme("/assets/style.css", CONF_VIEW_APP); ?>"/>
    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png", CONF_VIEW_APP); ?>"/>
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<div class="app">
    <header class="app_header">
        <h1><a class="icon-coffee transition" href="<?= url("/app"); ?>" title="CaféApp">CaféApp</a></h1>
        <ul class="app_header_widget">
            <li data-modalopen=".app_modal_contact" class="radius transition icon-life-ring">Precisa de ajuda?</li>
            <li data-mobilemenu="open" class="app_header_widget_mobile radius transition icon-menu icon-notext"></li>
        </ul>
    </header>

    <div class="app_box">
        <nav class="app_sidebar radius box-shadow">
            <div data-mobilemenu="close"
                 class="app_sidebar_widget_mobile radius transition icon-error icon-notext"></div>

            <div class="app_sidebar_user app_widget_title">
                <span class="user">
                    <?php if (user()->photo()): ?>
                        <img class="rounded" alt="Robson" title="Robson"
                             src="<?= image(user()->photo, 260, 260); ?>"/>
                    <?php else: ?>
                        <img class="rounded" alt="Robson" title="Robson"
                             src="<?= theme("/assets/images/avatar.jpg", CONF_VIEW_APP); ?>"/>
                    <?php endif; ?>
                    <span><?= user()->first_name; ?></span>
                </span>
                <span class="plan radius">Free</span>
            </div>

            <?= $v->insert("views/sidebar"); ?>
        </nav>

        <main class="app_main">
            <?= flash(); ?>
            <?= $v->section("content"); ?>
        </main>
    </div>

    <footer class="app_footer">
        <span class="icon-coffee">
            CaféApp - Desenvolvido na formação FSPHP<br>
            &copy; UpInside - Todos os direitos reservados
        </span>
    </footer>

    <?= $v->insert("views/modals"); ?>
</div>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-53658515-18"></script>
<script src="<?= theme("/assets/scripts.js", CONF_VIEW_APP); ?>"></script>
<?= $v->section("scripts"); ?>

</body>
</html>
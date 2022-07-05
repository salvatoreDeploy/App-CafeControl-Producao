<?php $v->layout("_login"); ?>

<div class="login">
    <article class="login_box radius">
        <h1 class="hl icon-coffee">Login</h1>
        <div class="ajax_response"><?= flash(); ?></div>

        <form name="login" action="<?= url("/admin/login"); ?>" method="post">
            <label>
                <span class="field icon-envelope">E-mail:</span>
                <input name="email" type="email" placeholder="Informe seu e-mail" required/>
            </label>

            <label>
                <span class="field icon-unlock-alt">Senha:</span>
                <input name="password" type="password" placeholder="Informe sua senha:" required/>
            </label>

            <button class="radius gradient gradient-green gradient-hover icon-sign-in">Entrar</button>
        </form>

        <footer>
            <p>Desenvolvido por www.<b>fsphp</b>.com.br</p>
            <p>&copy; <?= date("Y"); ?> - todos os direitos reservados</p>
            <a target="_blank"
               class="icon-whatsapp transition"
               href="https://api.whatsapp.com/send?phone=5515988171471&text=Olá, preciso de ajuda com o login."
            >WhatsApp: (15)9 8817 1471</a>
        </footer>
    </article>
</div>
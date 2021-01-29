<?php defined('_EXEC') or die; ?>

        <section class="modal success" data-modal="success">
            <div class="content">
                <main>
                    <i class="fas fa-check-circle"></i>
                    <p></p>
                </main>
            </div>
        </section>
        <section class="modal alert" data-modal="alert">
            <div class="content">
                <main>
                    <i class="fas fa-exclamation-triangle"></i>
                    <p></p>
                    <div>
                        <a button-close><i class="fas fa-check"></i></a>
                    </div>
                </main>
            </div>
        </section>
        <script src="https://cdn.codemonkey.com.mx/monkeyboard/assets/js/jquery-2.1.4.min.js"></script>
        <script src="https://cdn.codemonkey.com.mx/monkeyboard/assets/js/valkyrie.min.js"></script>
        <script src="https://cdn.codemonkey.com.mx/monkeyboard/assets/js/scripts.js"></script>
        <script src="{$path.js}scripts.js"></script>
        <script defer src="https://kit.fontawesome.com/743152b0c5.js"></script>
        {$dependencies.js}
        {$dependencies.other}
    </body>
</html>

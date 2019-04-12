<?php $this->beginPage() ?>

<!DOCTYPE html>
<!--[if IE 9]><html class="no-js lt-ie10" lang="ru"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="ru"><!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>APANEL</title>

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

        <?php $this->head() ?>
    </head>

    <body>
        <?php
            $this->beginBody();
            echo $content;
            $this->endBody();
        ?>

        <script>
            $(function() {
                Login.init();
            });
        </script>
    </body>
</html>

<?php $this->endPage() ?>


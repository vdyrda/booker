<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Boardroom Booker</title>
        <!-- stylesheets -->
        <link href="<?php echo VENDOR_PATH."bootstrap".DS."css".DS; ?>bootstrap.css" rel="stylesheet">
        <link href="<?php echo CSS_PATH; ?>style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700" rel="stylesheet">
    </head>
    <body class="page">
        
        <header id="header">
            <div class="container">
                <?php echo $html->header; ?>
            </div>
        </header>
        
        <section id="page_content">
            <div class="container">
                <?php echo $html->content; ?>
            </div>
        </section>
        
        <footer id="footer">
            <div class="container">
                <p class="copy">Copyright <?php echo date("Y", time()); ?>, Vladimir Dyrda</p>
            </div>
        </footer>
        
    </body>
    <!-- scripts -->
    <script src="<?php echo JS_PATH; ?>jquery-3.1.1.min.js"></script>
    <script src="<?php echo VENDOR_PATH."bootstrap".DS."js".DS; ?>bootstrap.min.js"></script>
    <script src="<?php echo JS_PATH; ?>jquery.validate.min.js"></script>
    <script src="<?php echo JS_PATH; ?>script.js"></script>
</html>
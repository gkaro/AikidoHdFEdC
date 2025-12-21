<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= $this->Html->meta("csrfToken", $this->request->getAttribute("csrfToken"));?>
    

        <link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Ruda:400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <link rel="stylesheet" href="<?= $this->Url->build('/css/style.css') ?>"/>
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        
        <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script src="<?= $this->Url->build('/js/materialize.js') ?>"></script>
        <title><?= $this->fetch('title') ?></title>
        
    </head>
    <body>
        <script>
            $(document).ready(function () {
                $('.dropdown-trigger').dropdown({
                    constrainWidth:false,
                    hover: false 
                }); 
                $('.sidenav').sidenav();
            });
        </script>
        <!--Menu Principal version Desktop-->
            <nav>
                <div class="nav-wrapper">
                    <a href="<?= $this->Url->build('/') ?>" class="brand-logo left hide-on-small-only">Stages Ligue HDF</a>
                    <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="<?= $this->Url->build('/edc-courses') ?>">Stages</a></li>
                        <li><a href="<?= $this->Url->build('/edc-members') ?>">Pratiquants</a></li>
                        <li><a href="<?= $this->Url->build('/stats') ?>">Stats</a></li>
                        <li><a href="<?= $this->Url->build('/config') ?>">Configuration</a></li>
                        <li><a href="<?= $this->Url->build('/config') ?>">Import licences</a></li>
                    </ul>
                </div>
            </nav>

            <!--Menu Principal version mobile -->
            
            <ul class="sidenav" id="mobile-demo">
                <li><a href="<?= $this->Url->build('/') ?>">Accueil</a></li>
                <li><a href="<?= $this->Url->build('/edc-courses') ?>">Stages</a></li>
                <li><a href="<?= $this->Url->build('/edc-members') ?>">Pratiquants</a></li>
            </ul>

        <main class="main">
            <div class="container">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>

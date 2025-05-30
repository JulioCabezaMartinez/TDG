<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TDG</title>
    <link rel="stylesheet" href="/TDG/public/CSS/style.css">
    <link rel="stylesheet" href="/TDG/public/CSS/header.css">
    <link rel="stylesheet" href="/TDG/public/CSS/footer.css">
    <link rel="stylesheet" href="/TDG/public/CSS/paginacion.css">
    
    <!-- Paypal -->
    <script src="<?php echo $_ENV["PAYPAL_SRC"] ?>"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <!-- Swiper.js (Carrusel y paginación de este) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    <!-- CSS propio del Carrusel -->
    <link rel="stylesheet" href="/TDG/public/CSS/carrusel.css">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CSS propio de cada vista -->
    <link rel="stylesheet" href="<?php echo "/TDG/public/CSS/{$css}.css" ?>">

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/6af6746610.js" crossorigin="anonymous"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
            $(".fa-bars").click(function(){
                $(".menu-hamburguesa").toggleClass("active");
            });

            $(".cerrar-hamburguesa").click(function(){
                $(".menu-hamburguesa").toggleClass("active");
            });
        });
    </script>
</head>
<body>
    

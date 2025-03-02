<html>

<head>
    <title>Finance Tracker</title>
    <link rel="stylesheet" href="css/index.css?v=1.0" />
    <link href="css/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="icon" href="img/logo2.png" type="image/png">
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="css/dist/js/select2.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/FullCalendar/fullcalendar.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="css/FullCalendar/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
        include "template/sidebar.php";
        include "template/content.php";    
        
        function sweetAlert($icon, $title, $text, $redirect = null) {
            $script = "<script>
                Swal.fire({
                    icon: '$icon',
                    title: '$title',
                    text: '$text'
                })";
            if ($redirect) {
                $script .= ".then(() => { window.location.href = '$redirect'; })";
            }
            $script .= ";</script>";
            echo $script;
        }

    ?>
</body>

</html>
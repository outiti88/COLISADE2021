<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Colisade Delivery Tanger propose
    un service de collecte, de stockage, d’emballage et d’expédition de vos produits aux clients.">
    <meta name="author" content="Outiti Ayoub">
    <meta name="keywords" content="Colisade,Delivery,Tanger,Livraison,Expédition,Collecte">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('/assets/images/favicon.png')}}">
    <title>@yield('title')</title>

    <link href="{{ url('/sass/style.min.css') }}" rel="stylesheet">
    <link href="{{ url('/css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ url('/assets/vendor/nucleo/css/nucleo.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('/assets/vendor/fortawesome/fontawesome-free/css/all.min.css')}}"  type="text/css">
    <link rel="stylesheet" href="{{ url('/css/argon.css')}}"  type="text/css">


    <link rel="stylesheet" href="{{ url('/otika/assets/bundles/owlcarousel2/dist/assets/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('/otika/assets/bundles/owlcarousel2/dist/assets/owl.theme.default.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('/otika/assets/bundles/summernote/summernote-bs4.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('/otika/assets/css/style.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('/otika/assets/css/components.css')}}" type="text/css">
    <link rel="stylesheet" href="{{ url('/otika/assets/css/custom.css')}}" type="text/css">

    <link href="{{ url('/assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css" rel="stylesheet">
    <style>


            ::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
            border-radius: 10px;
            background-color: #F5F5F5;
        }

            ::-webkit-scrollbar
        {
            width: 8px;
            background-color: #F5F5F5;
        }

            ::-webkit-scrollbar-thumb
        {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
            background-color: #f7941e ;
        }
    </style>
    @yield('style')


</head>

<body id=style-1>

    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">

        @include('partiels._navbar')

        @include('partiels._sidebar')

        <div class="page-wrapper">

            @yield('content')
            <footer class="footer text-center">
                Copyright © 2021 All rights reserved | Made with <i class="mdi mdi-heart" aria-hidden="true"></i>  by <a href="https://idesignbusiness.ma//" target="_blank">iDesign Business</a>
            </footer>
        </div>

    </div>

    <script>
        var xx = document.getElementById("secteur");
        function myFunction() {
        var test = document.getElementById("ville").value;
        if(test=='Tanger'){
            xx.style.display = "block";
        }
        else{
            xx.style.display = "none";
        }
        }
    </script>

    <script>
        var yy = document.getElementById("montant");
        function myFunction2(mode) {
            if(mode == 'cd'){
                yy.style.display = "block";
                console.log("cd");
            }
            else{
                yy.style.display = "none";
                console.log("cp");
            }
        }

    </script>



    <script src="{{ url('/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ url('/otika/assets/js/app.min.js') }}"></script>
    <script src="{{ url('/otika/assets/js/scripts.js') }}"></script>

    <script src="{{ url('/otika/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ url('/otika/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('/otika/assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ url('/otika/assets/js/page/datatables.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ url('/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ url('/js/app-style-switcher.js') }}" ></script>
    <!--Wave Effects -->
    <!--Menu sidebar -->
    <script src="{{ url('/js/sidebarmenu.js') }}" ></script>
    <!--Custom JavaScript -->
    <script src="{{ url('/js/custom.js') }}" ></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>


    @yield('javascript')
</body>

</html>

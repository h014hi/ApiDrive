<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Google Drive Api</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha2/dist/css/bootstrap.min.css" integrity="...">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="..." crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="..." crossorigin="anonymous"></script>

</head>
<body>
    <div class="container mt-3">
        <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">
            <h1 class="display-4 text-center font-weight-bold text-primary">API GOOGLE DRIVE</h1>
        </a>
    </div>
    <div class="container">
        @yield('content')
    </div>

    <!--<footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <p class="mb-0"><strong>Integrantes:</strong></p>
            <p class="mb-0">Roni Wagner Itusaca Maldonado</p>
            <p class="mb-0">Ruth Erika Pampacata Chipana</p>
            <p class="mb-3">Royer Huanca Matiaz Cardoza</p>
            <p class="mb-0"><strong>Docente:</strong></p>
            <p class="mb-3">Bejar Gonzales Victor Hugo</p>
            <p class="mb-0"><strong>Curso:</strong></p>
            <p class="mb-0">Integración de Sistemas de Software</p>
        </div>
    </footer>-->
</body>
</html>

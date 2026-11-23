<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClassIQ | Brain Boost')</title>

   
    <!-- links -->

  <!-- links -->
  <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('images/Fevicon.png') }}">

  <!-- icon -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <!-- for slider -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Gloria+Hallelujah&display=swap">

  <!-- css -->

  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

  @stack('styles') 




</head>
<body>

    @include('layouts.lay_navbar')




    @yield('content')

    @include('layouts.lay_footer')



  <script src="{{ asset('js/hero.js') }}"></script>
</body>
</html>

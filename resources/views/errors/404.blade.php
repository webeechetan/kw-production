@php
    $id = request()->route('task');
    $task = App\Models\Task::withoutGlobalScope(
        \App\Models\Scopes\OrganizationScope::class
    )->find($id);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--- Meta Tag for SEO --->
    <meta name="description" content="Login to review the task on the web app">
    <meta name="keywords" content="Kaykewalk, Team Management, Client Management">
    <meta name="author" content="Kaykewalk">

    <!--- Favicon --->
    <link rel="icon" href="{{ asset('') }}assets/images/fav.png" />

    <!---- Open Graph Meta Tags --->
    <meta property="og:title" content="Login to review the task on the web app">
    <meta property="og:description" content="Login to review the task on the web app">
    <meta property="og:image" content="{{ asset('') }}assets/images/fav.png">
    <meta property="og:url" content="{{ route('login') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:site_name" content="Kaykewalk">
    <meta name="twitter:image:alt" content="Login to review the task on the web app">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!--- Title --->
    <title>@if($task){{ $task->name }}@else Requested route not found  @endif</title>

    <style>
        .error-page {
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 100vh;
            font-family: Jost, sans-serif;

        }
        .error-body {
            padding: 40px;
        }
        .error-btn {
            min-width: 120px;
            height: 40px;
            display: inline-flex;
            background: #7407FF;
            color: #fff;
            text-decoration: none;
            align-items: center;
            justify-content: center;
            border-radius: 30px;
            transition: all 0.5s ease;
            margin-top: 20px;
        }
        .error-btn:focus, .error-btn:hover {
            background-color: #000;
        }

        h1, h2 {
            font-weight: 500;
        }

        h1 {
            font-size: 3em;
            margin-bottom: 0;
        }
        dotlottie-player {
            margin: -70px auto 0;
        }
    </style>

</head>
<body>
    <div class="error-page">
        <div class="error-body">
            <dotlottie-player src="https://lottie.host/644d7824-72cd-4a03-8cfb-e64e010591f8/2SN3cXzzHl.lottie" background="transparent" speed="1" style="width: 40%; height: 20%" loop autoplay></dotlottie-player>
            <h1>4O4 Page Not Found</h1>
            <h2>The page you were looking for could not be found</h2>
            <p>Please login to access the page you are looking for.</p>
            <a class="error-btn" href="{{ route('login') }}">Login</a>
        </div>
    </div>

    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
</body>
</html>
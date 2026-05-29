<!DOCTYPE html>
<html lang="hi-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

@php
    $siteName = 'A7-SATTAFAST';

    $metaTitle = $seo->meta_title
        ?? 'A7 Satta King Result Today 2026 | A7 SattaFast Live Result Chart';

    $metaDescription = $seo->meta_description
        ?? 'A7 SattaFast par daily live result, satta king chart, record chart aur yearly result chart fast update ke saath dekhein.';

    $metaKeywords = $seo->meta_keywords
        ?? 'a7 satta, a7 satta king, a7 satta result, a7 satta fast, satta king result, satta chart, gali result, disawar result, faridabad result, ghaziabad result';

    $canonicalUrl = $seo->canonical_url ?? url()->current();

    $ogTitle = $seo->og_title ?? $metaTitle;
    $ogDescription = $seo->og_description ?? $metaDescription;

    $ogImage = !empty($seo->og_image)
        ? asset($seo->og_image)
        : url('A1.png');

  
@endphp

<title>{{ $metaTitle }}</title>

<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="author" content="{{ $siteName }}">
<meta name="robots" content="index, follow">

<link rel="canonical" href="{{ $canonicalUrl }}">

<meta property="og:type" content="website">
<meta property="og:title" content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $siteName }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle }}">
<meta name="twitter:description" content="{{ $ogDescription }}">
<meta name="twitter:image" content="{{ $ogImage }}">

@if(!empty($seo?->schema_markup))
    {!! $seo->schema_markup !!}
@endif

    <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/style.css') }}">
    <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/background.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        p { margin: 0; }
        .open { font-size: 35px; }
        .logo {
            height: 70px;
            width: 70px;
            border-radius: 50%;
            padding: 10px;
        }
        .text-black { color: black; font-size: 13px; }
        .text-blacks { color: black; font-size: 15px; }
        .time_result, .time {
            color: blue;
            font-size: 15px;
            font-weight: bold;
        }
        .kal {
            color: #000;
            font-size: 17px;
        }
    </style>
</head>

<body>

    @include('front.layouts.header')

    @yield('content')

    @include('front.layouts.footer')

    @yield('custom-script')

</body>
</html>
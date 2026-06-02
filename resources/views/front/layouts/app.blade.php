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
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}

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



<style>
    .rv-ad-wrap{
        width:100%;
        margin:12px auto;
        font-family:Arial,'Noto Sans Devanagari',sans-serif;
    }

    .rv-ad-box{
        background:linear-gradient(180deg,#ffd900 0%,#fff8cf 100%);
        border:3px dashed #e60000;
        border-radius:16px;
        padding:12px 10px;
        text-align:center;
        overflow:hidden;
        box-shadow:0 4px 12px rgba(0,0,0,.10);
    }

    .rv-ad-box,
    .rv-ad-box *{
        color:#111!important;
        font-size:16px!important;
        font-weight:700!important;
        line-height:1.45!important;
        word-break:break-word;
    }

    .rv-ad-box h1,
    .rv-ad-box h2,
    .rv-ad-box h3,
    .rv-ad-box h4,
    .rv-ad-box h5,
    .rv-ad-box h6,
    .rv-ad-box p,
    .rv-ad-box div{
        margin:4px 0!important;
        font-size:16px!important;
    }

    .rv-ad-title{
        font-size:18px!important;
        font-weight:800!important;
    }

    .rv-ad-name{
        font-size:19px!important;
        font-weight:900!important;
        color:#c9342d!important;
    }

    .rv-ad-purple{
        color:#9b59b6!important;
        font-weight:800!important;
    }

    .rv-ad-img{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#fff;
        border-radius:999px;
        padding:5px 12px;
        margin-top:8px;
        max-width:100%;
    }

    .rv-ad-img img{
        width:auto;
        height:auto;
        max-height:55px;
        max-width:200px;
        object-fit:contain;
    }

    .rv-middle{
        background:linear-gradient(180deg,#111827,#1f2937);
        border:3px dashed #ffd900;
    }

    .rv-middle,
    .rv-middle *{
        color:#fff!important;
    }

    .rv-middle .rv-ad-img img{
        max-height:55px;
        max-width:200px;
    }

    .rv-result-title{
        text-align:center;
        margin:12px 0 8px;
        font-size:20px;
        font-weight:900;
    }

    .rv-result-title a{
        text-decoration:none;
        color:#111;
    }

    .rv-result-title span{
        color:#c9342d;
    }

    @media(max-width:640px){
        .rv-ad-wrap{
            margin:10px auto;
        }

        .rv-ad-box{
            border-width:3px;
            border-radius:14px;
            padding:10px 8px;
        }

        .rv-ad-box,
        .rv-ad-box *{
            font-size:14px!important;
            line-height:1.4!important;
            font-weight:700!important;
        }

        .rv-ad-box h1,
        .rv-ad-box h2,
        .rv-ad-box h3,
        .rv-ad-box h4,
        .rv-ad-box h5,
        .rv-ad-box h6,
        .rv-ad-box p,
        .rv-ad-box div{
            font-size:14px!important;
        }

        .rv-ad-title{
            font-size:15px!important;
        }

        .rv-ad-name{
            font-size:16px!important;
        }

        .rv-ad-img{
            padding:4px 10px;
            margin-top:6px;
        }

        .rv-ad-img img{
            max-height:48px;
            max-width:175px;
        }

        .rv-result-title{
            font-size:16px;
        }
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
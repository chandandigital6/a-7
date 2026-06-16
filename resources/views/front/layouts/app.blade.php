<!DOCTYPE html>
<html lang="hi-IN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="QO-2oh9N--tEzzpguKQYG592l_PY34GEfQaX8QTZyig">

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
    <meta name="theme-color" content="#ffd900">

    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="hi_IN">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $ogDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="preconnect" href="{{ url('/') }}" crossorigin>
    <link rel="preload" href="{{ asset('A1.png') }}" as="image" fetchpriority="high">

    <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tamplate/bootstrap/assests1/style.css') }}">

    @if(!empty($seo?->schema_markup))
        {!! $seo->schema_markup !!}
    @endif

    <style>
        html {
            scroll-behavior: smooth;
            text-size-adjust: 100%;
        }

        body {
            margin: 0;
            width: 100%;
            min-height: 100vh;
            background: #fff;
            color: #111;
            font-family: Arial, 'Noto Sans Devanagari', sans-serif;
            overflow-x: hidden;
        }

        main {
            display: block;
            width: 100%;
            min-height: 400px;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        p {
            margin: 0;
        }

        a {
            text-underline-offset: 2px;
        }

        .open {
            font-size: 35px;
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            padding: 10px;
            object-fit: contain;
        }

        .text-black,
        .text-blacks {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 48px;
            min-height: 34px;
            padding: 4px 8px;
            line-height: 1.25;
            color: #000;
            text-decoration: none;
        }

        .text-black {
            font-size: 15px;
        }

        .text-blacks {
            font-size: 17px;
            font-weight: 800;
        }

        .time_result,
        .time {
            color: blue;
            font-size: 15px;
            font-weight: bold;
        }

        .kal {
            color: #000;
            font-size: 17px;
        }

        .rv-ad-wrap {
            width: 100%;
            margin: 12px auto;
            font-family: Arial, 'Noto Sans Devanagari', sans-serif;
            contain: layout paint;
        }

        .rv-ad-box {
            min-height: 112px;
            background: linear-gradient(180deg, #ffd900 0%, #fff8cf 100%);
            border: 3px dashed #e60000;
            border-radius: 16px;
            padding: 12px 10px;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .10);
        }

        .rv-ad-box,
        .rv-ad-box * {
            color: #111 !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            line-height: 1.45 !important;
            word-break: break-word;
        }

        .rv-ad-box h1,
        .rv-ad-box h2,
        .rv-ad-box h3,
        .rv-ad-box h4,
        .rv-ad-box h5,
        .rv-ad-box h6,
        .rv-ad-box p {
            margin: 4px 0 !important;
            font-size: 16px !important;
        }

        .rv-ad-box > div:not(.addb-content) {
            margin: 4px 0 !important;
            font-size: 16px !important;
        }

        .addb-content {
            display: block !important;
            width: 100% !important;
            text-align: center !important;
            white-space: pre-line !important;
            margin: 0 auto !important;
        }

        .addb-content p,
        .addb-content div {
            display: block !important;
            margin: 4px 0 !important;
            padding: 0 !important;
            text-align: center !important;
            white-space: pre-line !important;
        }

        .addb-content br {
            display: block !important;
            content: "" !important;
            margin: 0 !important;
            line-height: 1.2 !important;
        }

        .addb-content img {
            width: 159px !important;
            height: 55px !important;
            max-width: 220px !important;
            object-fit: contain !important;
        }

        .addb-content strong,
        .addb-content b {
            font-weight: 900 !important;
        }

        .addb-content ul,
        .addb-content ol {
            display: inline-block !important;
            margin: 4px auto !important;
            padding-left: 20px !important;
            text-align: left !important;
        }

        .addb-content li {
            margin: 2px 0 !important;
            white-space: normal !important;
        }

        .rv-ad-title {
            font-size: 18px !important;
            font-weight: 800 !important;
        }

        .rv-ad-name {
            font-size: 19px !important;
            font-weight: 900 !important;
            color: #b42318 !important;
        }

        .rv-ad-purple {
            color: #7e22ce !important;
            font-weight: 800 !important;
        }

        .rv-ad-img {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 58px;
            max-width: 100%;
            margin-top: 8px;
            padding: 5px 12px;
            background: #fff;
            border-radius: 999px;
        }

        .rv-ad-img img {
            width: 159px;
            height: 55px;
            max-width: 220px;
            object-fit: contain;
        }

        .rv-ad-img.rv-small-img img {
            width: 139px;
            height: 48px;
        }

        .rv-middle {
            background: linear-gradient(180deg, #111827, #1f2937);
            border: 3px dashed #ffd900;
        }

        .rv-middle,
        .rv-middle * {
            color: #fff !important;
        }

        .rv-result-title {
            text-align: center;
            margin: 12px 0 8px;
            font-size: 20px;
            font-weight: 900;
            line-height: 1.3;
        }

        .rv-result-title a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 4px 10px;
            text-decoration: none;
            color: #111;
        }

        .rv-result-title span {
            color: #c9342d;
        }

        @media(max-width: 640px) {
            .rv-ad-wrap {
                margin: 10px auto;
            }

            .rv-ad-box {
                min-height: 104px;
                border-width: 3px;
                border-radius: 14px;
                padding: 10px 8px;
            }

            .rv-ad-box,
            .rv-ad-box * {
                font-size: 14px !important;
                line-height: 1.4 !important;
            }

            .addb-content,
            .addb-content p,
            .addb-content div {
                white-space: pre-line !important;
            }

            .addb-content img {
                width: 159px !important;
                height: 55px !important;
                max-width: 190px !important;
            }

            .rv-ad-title {
                font-size: 15px !important;
            }

            .rv-ad-name {
                font-size: 16px !important;
            }

            .rv-ad-img {
                min-height: 52px;
                padding: 4px 10px;
                margin-top: 6px;
            }

            .rv-ad-img img {
                width: 159px;
                height: 55px;
                max-width: 190px;
            }

            .rv-ad-img.rv-small-img img {
                width: 139px;
                height: 48px;
            }

            .rv-result-title {
                font-size: 16px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('front.layouts.header')

    <main id="main-content" role="main">
        @yield('content')
    </main>

    @include('front.layouts.footer')

    @yield('custom-script')
    @stack('scripts')
</body>
</html>
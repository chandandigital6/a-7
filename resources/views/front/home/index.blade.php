@extends('front.layouts.app', [
    'seo' => $seo ?? null,
])

@section('content')
    @php
        /*
        |--------------------------------------------------------------------------
        | Safety: cache/incomplete object issue fix
        |--------------------------------------------------------------------------
        */
        if ($games instanceof \__PHP_Incomplete_Class) {
            $games = collect();
        } else {
            $games = collect($games ?? []);
        }

        if ($chartGames instanceof \__PHP_Incomplete_Class) {
            $chartGames = collect();
        } else {
            $chartGames = collect($chartGames ?? []);
        }

        if ($monthlyResults instanceof \__PHP_Incomplete_Class) {
            $monthlyResults = collect();
        } else {
            $monthlyResults = collect($monthlyResults ?? []);
        }

        if ($advertisements instanceof \__PHP_Incomplete_Class) {
            $advertisements = collect();
        } else {
            $advertisements = collect($advertisements ?? []);
        }

        if (!function_exists('formatGameResult')) {
            function formatGameResult($value)
            {
                if ($value === null || $value === '') {
                    return 'XX';
                }

                return is_numeric($value) && (int) $value <= 9
                    ? str_pad($value, 2, '0', STR_PAD_LEFT)
                    : $value;
            }
        }

        if (!function_exists('renderAdContent')) {
            function renderAdContent($html)
            {
                if (empty($html)) {
                    return '';
                }

                $html = preg_replace('/<img(?![^>]*\bwidth=)/i', '<img width="159"', $html);
                $html = preg_replace('/<img(?![^>]*\bheight=)/i', '<img height="55"', $html);
                $html = preg_replace('/<img(?![^>]*\bloading=)/i', '<img loading="lazy"', $html);
                $html = preg_replace('/<img(?![^>]*\bdecoding=)/i', '<img decoding="async"', $html);

                return $html;
            }
        }

        $middleAdvertisement = $advertisements->where('position', 'middle')->first();
        $bottomAdvertisement = $advertisements->where('position', 'bottom')->first();
        $sidebarAdvertisement = $advertisements->where('position', 'sidebar')->first();

        $gameSections = $games->chunk(18);

        $chartGameSections = $chartGames->count() > 0
            ? $chartGames->chunk(max(1, ceil($chartGames->count() / 2)))
            : collect();

        $currentYear = now('Asia/Kolkata')->year;

        $recordYears = [
            $currentYear,
            $currentYear - 1,
            $currentYear - 2,
        ];
    @endphp

    <style>
        .a7-page {
            width: 100%;
            overflow-x: hidden;
            background: #fff;
        }

        .a7-live-title {
            text-align: center;
            margin: 12px 0 8px;
            font-size: 20px;
            font-weight: 900;
            line-height: 1.3;
        }

        .a7-live-title a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 6px 12px;
            text-decoration: none;
            color: #111;
        }

        .a7-live-title span {
            color: #c9342d;
        }

        .a7-gamebox {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            min-height: 126px;
            background: #fff;
        }

        .a7-gamebox a {
            text-decoration: none;
        }

        .a7-game-name {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 4px 8px;
            color: #000;
            font-size: 17px;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.25;
        }

        .a7-record-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 4px 8px;
            color: #000;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.25;
        }

        .a7-time-result {
            color: blue;
            font-size: 15px;
            font-weight: bold;
            line-height: 1.45;
        }

        .a7-kal {
            color: #000;
            font-size: 17px;
            font-weight: 800;
        }

        .a7-game-result {
            color: #000;
            font-size: 22px;
            font-weight: 900;
            line-height: 1.2;
        }

        .a7-arrow {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .a7-section-heading {
            background-color: #fff;
        }

        .a7-section-heading h2 {
            margin: 0;
            text-align: center;
            padding: 10px;
            color: red;
            font-size: 22px;
            line-height: 1.3;
            font-weight: 900;
        }

        .a7-drag {
            text-align: center;
            padding: 10px;
        }

        .a7-drag h2 {
            margin: 0 0 5px;
            font-size: 24px;
            font-weight: 900;
            color: #111;
        }

        .a7-drag a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 5px 10px;
            color: #0047ff;
            font-size: 18px;
            font-weight: 800;
            text-decoration: none;
        }

        .a7-chart-table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            content-visibility: auto;
            contain-intrinsic-size: 600px;
        }

        .a7-chart-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .a7-chart-table th {
            font-size: 14px;
            white-space: nowrap;
            background-color: #cc4c1a;
            color: #fff;
            text-align: center;
            padding: 8px 10px;
            text-transform: uppercase;
            border: 1px solid #111;
        }

        .a7-chart-table .date-col {
            font-size: 18px;
            background-color: #3333ff;
            color: #fff;
            text-align: center;
            font-weight: bold;
            padding: 8px 10px;
        }

        .a7-chart-table td {
            font-size: 15px;
            font-weight: bold;
            background-color: #fff;
            color: #111;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #111;
        }

        .a7-content-section {
            background: #050505;
            padding: 30px 12px;
            font-family: Arial, sans-serif;
            color: #fff;
            content-visibility: auto;
            contain-intrinsic-size: 1600px;
        }

        .a7-container {
            max-width: 1100px;
            margin: auto;
        }

        .a7-title {
            background: linear-gradient(135deg, #ffcc00, #ff6600);
            color: #000;
            padding: 18px;
            border-radius: 12px;
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            margin: 0 0 25px;
            line-height: 1.4;
        }

        .a7-content-box {
            background: #111;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 18px;
        }

        .a7-content-box h2 {
            color: #ffcc00;
            font-size: 24px;
            margin: 0 0 12px;
            line-height: 1.4;
        }

        .a7-content-box p {
            color: #eee;
            font-size: 16px;
            line-height: 1.9;
            margin-bottom: 14px;
        }

        .a7-content-box ul {
            padding-left: 20px;
            margin-top: 10px;
        }

        .a7-content-box li {
            color: #eee;
            font-size: 16px;
            line-height: 1.9;
            margin-bottom: 8px;
        }

        .a7-faq-title {
            background: #003cff;
            color: #fff;
            padding: 14px;
            text-align: center;
            border-radius: 10px;
            margin: 30px 0 20px;
            font-size: 26px;
            font-weight: 800;
        }

        .a7-card {
            background: #111;
            border: 1px solid #333;
            border-radius: 12px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .a7-card summary {
            cursor: pointer;
            padding: 16px;
            min-height: 48px;
            font-size: 19px;
            font-weight: 700;
            color: #ffcc00;
            background: #1a1a1a;
        }

        .a7-card div {
            padding: 18px;
            line-height: 1.8;
            color: #eee;
            font-size: 16px;
        }

        .a7-record-section {
            background: #000;
            padding-bottom: 25px;
            content-visibility: auto;
            contain-intrinsic-size: 1200px;
        }

        .a7-record-heading {
            background: #f5004f;
            color: #fff;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            padding: 14px 5px;
            border-top: 3px solid #fff;
            border-bottom: 3px solid #fff;
            text-transform: uppercase;
        }

        .a7-record-link-box {
            background: #fff;
            border: 2px solid blue;
            border-radius: 18px;
            margin: 0 0 30px;
            text-align: center;
            padding: 10px;
            font-size: 24px;
            color: #000;
        }

        .a7-record-link-box a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 4px 8px;
            color: #000;
            text-decoration: none;
            font-weight: 800;
            line-height: 1.3;
        }

        @media (max-width: 767px) {
            .a7-gamebox {
                width: 50%;
                float: left;
                min-height: 134px;
            }
        }

        @media (max-width: 768px) {
            .a7-live-title {
                font-size: 17px;
            }

            .a7-section-heading h2 {
                font-size: 18px;
            }

            .a7-title {
                font-size: 22px;
                padding: 15px;
            }

            .a7-content-box {
                padding: 18px;
            }

            .a7-content-box h2 {
                font-size: 21px;
            }

            .a7-faq-title {
                font-size: 22px;
            }

            .a7-record-heading {
                font-size: 21px;
            }

            .a7-record-link-box {
                font-size: 19px;
            }
        }
    </style>

    <div class="a7-page">

        <div class="drag">
            <h1 class="a7-live-title rv-result-title">
                <a href="/">
                    <span>A7-SATTAFAST LIVE RESULT</span>
                </a>
            </h1>

            @if ($middleAdvertisement)
                <section class="rv-ad-wrap" aria-label="Middle Advertisement">
                    <div class="rv-ad-box">
                        @if (!empty($middleAdvertisement->content))
                            <div class="addb-content">
                                {!! renderAdContent($middleAdvertisement->content) !!}
                            </div>
                        @elseif(!empty($middleAdvertisement->title))
                            <div class="rv-ad-name">
                                {{ $middleAdvertisement->title }}
                            </div>
                        @endif

                        @if (!empty($middleAdvertisement->image))
                            @if (!empty($middleAdvertisement->link))
                                <a href="{{ $middleAdvertisement->link }}" target="_blank" rel="noopener nofollow" style="text-decoration:none;">
                                    <span class="rv-ad-img">
                                        <img src="{{ asset('storage/' . $middleAdvertisement->image) }}"
                                             alt="{{ $middleAdvertisement->title ?? 'Advertisement' }}"
                                             width="159"
                                             height="55"
                                             loading="eager"
                                             decoding="async"
                                             fetchpriority="high">
                                    </span>
                                </a>
                            @else
                                <span class="rv-ad-img">
                                    <img src="{{ asset('storage/' . $middleAdvertisement->image) }}"
                                         alt="{{ $middleAdvertisement->title ?? 'Advertisement' }}"
                                         width="159"
                                         height="55"
                                         loading="eager"
                                         decoding="async"
                                         fetchpriority="high">
                                </span>
                            @endif
                        @endif
                    </div>
                </section>
            @else
                <section class="rv-ad-wrap" aria-label="Middle Advertisement">
                    <div class="rv-ad-box">
                        <div class="rv-ad-title">सीधे सट्टा कंपनी का No 1 खाईवाल</div>

                        <div class="rv-ad-name">☆☆ ABHISHEK Bhai KHAIWAL ☆☆</div>

                        <div>
                            🎯 पालिका बाजार..1:20pm<br>
                            🎯 प्रयागराज........2:00pm<br>
                            🎯 दिल्लीबाजार ...3:00pm<br>
                            🎯 दिल्ली दरबार....3:30pm<br>
                            🎯 श्री गणेश........4:30 Pm<br>
                            🎯 रूप नगर..........5:10pm<br>
                            🎯 फरीदाबाद.......5:50 pm<br>
                            🎯 फतेहपुर..........7:10 pm<br>
                            🎯 गाजियाबाद......8:50 pm<br>
                            🎯 नोएडानाईट.....10:00 pm<br>
                            🎯 गली...............11:15pm<br>
                            🎯 दिसावर ..........3:00 am
                        </div>

                        <div>
                            जोड़ी रेट<br>
                            जोड़ी रेट 10-------960<br>
                            हरफ रेट 100-----960
                        </div>

                        <div class="rv-ad-name">☆☆ ABHISHEK Bhai KHAIWAL ☆☆</div>

                        <div class="rv-ad-purple">
                            Game Play करने के लिए नीचे लिंक पर क्लिक करे
                        </div>

                        <span class="rv-ad-img rv-small-img">
                            <img src="{{ asset('whatsAppChat.png') }}"
                                 alt="ABHISHEK Bhai"
                                 width="139"
                                 height="48"
                                 loading="eager"
                                 decoding="async"
                                 fetchpriority="high">
                        </span>

                        <div>Click to chat</div>
                    </div>
                </section>
            @endif
        </div>

        @if ($bottomAdvertisement)
            <section class="rv-ad-wrap" aria-label="Bottom Advertisement">
                <div class="rv-ad-box rv-middle">
                    @if (!empty($bottomAdvertisement->content))
                        <div class="addb-content">
                            {!! renderAdContent($bottomAdvertisement->content) !!}
                        </div>
                    @elseif(!empty($bottomAdvertisement->title))
                        <h2>{{ $bottomAdvertisement->title }}</h2>
                    @endif

                    @if (!empty($bottomAdvertisement->image))
                        @if (!empty($bottomAdvertisement->link))
                            <a href="{{ $bottomAdvertisement->link }}" target="_blank" rel="noopener nofollow" style="text-decoration:none;">
                                <span class="rv-ad-img">
                                    <img src="{{ asset('storage/' . $bottomAdvertisement->image) }}"
                                         alt="{{ $bottomAdvertisement->title ?? 'Advertisement' }}"
                                         width="159"
                                         height="55"
                                         loading="lazy"
                                         decoding="async">
                                </span>
                            </a>
                        @else
                            <span class="rv-ad-img">
                                <img src="{{ asset('storage/' . $bottomAdvertisement->image) }}"
                                     alt="{{ $bottomAdvertisement->title ?? 'Advertisement' }}"
                                     width="159"
                                     height="55"
                                     loading="lazy"
                                     decoding="async">
                            </span>
                        @endif
                    @endif
                </div>
            </section>
        @else
            <section class="rv-ad-wrap" aria-label="Bottom Advertisement">
                <div class="rv-ad-box rv-middle">
                    <h2>
                        व्हाट्सएप पर सुपर फास्ट रिजल्ट देखने के लिए नीचे दिए गए लिंक पर जाएं और चैनल को फॉलो करें।
                    </h2>

                    <a href="https://whatsapp.com/channel/0029Vb67katLikgE57Pwhj0T" target="_blank" rel="noopener nofollow" style="text-decoration:none;">
                        <span class="rv-ad-img">
                            <img src="{{ asset('Join-WhatsApp.png') }}"
                                 alt="Join WhatsApp"
                                 width="159"
                                 height="55"
                                 loading="lazy"
                                 decoding="async">
                        </span>
                    </a>
                </div>
            </section>
        @endif

        <section class="container-fluid" aria-label="A7 SattaFast Live Result">
            @foreach ($gameSections as $sectionIndex => $gameSection)
                <div class="{{ $sectionIndex > 0 ? 'mt-4' : '' }}">
                    <div class="a7-section-heading">
                        <div class="addb">
                            <h2>A7-SATTAFAST LIVE RESULT</h2>
                        </div>
                    </div>

                    <div class="border row">
                        @forelse($gameSection as $game)
                            @php
                                $todayResult = $game->todayResult->result ?? null;
                                $todayStatus = $game->todayResult->status ?? 'waiting';

                                $yesterdayResult = $game->yesterdayResult->result ?? null;
                                $yesterdayStatus = $game->yesterdayResult->status ?? 'waiting';
                            @endphp

                            <article class="a7-gamebox col-md-6 col-sm-6 col-xs-6">
                                <a class="a7-game-name text-blacks" href="{{ url('records/' . $game->slug) }}">
                                    {{ $game->name }}
                                </a>

                                <br>

                                <a class="a7-record-link text-black" href="{{ url('records/' . $game->slug) }}">
                                    Records
                                </a>

                                <br>

                                <span class="a7-time-result time_result">
                                    @if (!empty($game->result_time))
                                        {{ \Carbon\Carbon::parse($game->result_time)->format('h:i A') }}
                                    @endif

                                    <br>

                                    <span class="a7-kal kal">कल &nbsp;&nbsp; आज</span>
                                    <br>

                                    <span class="a7-game-result gameboxresult">
                                        @if ($yesterdayStatus === 'declared' && $yesterdayResult)
                                            {{ formatGameResult($yesterdayResult) }}
                                        @else
                                            XX
                                        @endif
                                    </span>

                                    <img class="a7-arrow"
                                         src="{{ asset('arrow.gif') }}"
                                         width="20"
                                         height="20"
                                         loading="lazy"
                                         decoding="async"
                                         alt="Arrow">
                                </span>

                                <span class="a7-game-result gameboxresult">
                                    @if ($todayStatus === 'declared' && $todayResult)
                                        {{ formatGameResult($todayResult) }}
                                    @else
                                        XX
                                    @endif
                                </span>
                            </article>
                        @empty
                            <div class="col-md-12 text-center p-3">
                                <strong>No game data found.</strong>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </section>

        @if ($sidebarAdvertisement)
            <section class="rv-ad-wrap" aria-label="Sidebar Advertisement">
                <div class="rv-ad-box">
                    @if (!empty($sidebarAdvertisement->content))
                        <div class="addb-content">
                            {!! renderAdContent($sidebarAdvertisement->content) !!}
                        </div>
                    @elseif(!empty($sidebarAdvertisement->title))
                        <h2 class="rv-ad-name">{{ $sidebarAdvertisement->title }}</h2>
                    @endif

                    @if (!empty($sidebarAdvertisement->image))
                        @if (!empty($sidebarAdvertisement->link))
                            <a href="{{ $sidebarAdvertisement->link }}" target="_blank" rel="noopener nofollow" style="text-decoration:none;">
                                <span class="rv-ad-img">
                                    <img src="{{ asset('storage/' . $sidebarAdvertisement->image) }}"
                                         alt="{{ $sidebarAdvertisement->title ?? 'Advertisement' }}"
                                         width="159"
                                         height="55"
                                         loading="lazy"
                                         decoding="async">
                                </span>
                            </a>
                        @else
                            <span class="rv-ad-img">
                                <img src="{{ asset('storage/' . $sidebarAdvertisement->image) }}"
                                     alt="{{ $sidebarAdvertisement->title ?? 'Advertisement' }}"
                                     width="159"
                                     height="55"
                                     loading="lazy"
                                     decoding="async">
                            </span>
                        @endif
                    @endif
                </div>
            </section>
        @else
            <section class="rv-ad-wrap" aria-label="Sidebar Advertisement">
                <div class="rv-ad-box">
                    <div class="rv-ad-title">सीधे सट्टा कंपनी का No 1 खाईवाल</div>

                    <div class="rv-ad-name">☆☆ ABHISHEK Bhai KHAIWAL ☆☆</div>

                    <div>
                        🎯 पालिका बाजार.1:20pm<br>
                        🎯 प्रयागराज........2:00pm<br>
                        🎯 दिल्लीबाजार ...3:00pm<br>
                        🎯 श्री गणेश.........4:0 Pm<br>
                        🎯 रूप नगर..........5:10pm<br>
                        🎯 फरीदाबाद.......5:50 pm<br>
                        🎯 फतेहपुर..........7:10 pm<br>
                        🎯 गाजियाबाद......8:50 pm<br>
                        🎯 नोएडानाईट.....10:00 pm<br>
                        🎯 गली...............11:15pm<br>
                        🎯 दिसावर ..........3:00 am
                    </div>

                    <div>
                        जोड़ी रेट<br>
                        जोड़ी रेट 10-------960<br>
                        हरफ रेट 100-----960
                    </div>

                    <div class="rv-ad-name">☆☆ ABHISHEK Bhai KHAIWAL ☆☆</div>

                    <div class="rv-ad-purple">
                        Game Play करने के लिये नीचे लिंक पर क्लिक करे
                    </div>

                    <a href="https://wa.me/919896916793" target="_blank" rel="noopener nofollow" style="text-decoration:none;">
                        <span class="rv-ad-img rv-small-img">
                            <img src="{{ asset('Wp.png') }}"
                                 alt="ABHISHEK Bhai"
                                 width="139"
                                 height="48"
                                 loading="lazy"
                                 decoding="async">
                        </span>
                    </a>

                    <div>Click to chat</div>
                </div>
            </section>
        @endif

        <div class="a7-drag drag">
            <h2>A7-SATTAFAST RECORD CHART</h2>
            <a href="/">A7-SATTAFAST LIVE RESULT</a>
        </div>

        <div class="a7-drag drag">
            <a href="/">A7-SATTAFAST Chart</a>
        </div>

        @foreach ($chartGameSections as $sectionIndex => $sectionChartGames)
            <div class="a7-chart-table-wrap table-responsive {{ $sectionIndex > 0 ? 'mt-4' : '' }}">
                <table class="a7-chart-table month_result_table rtable">
                    <thead>
                        <tr>
                            <th>DATE</th>

                            @foreach ($sectionChartGames as $chartGame)
                                <th>{{ $chartGame->name }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($dates as $date)
                            @php
                                $dateKey = $date->format('Y-m-d');
                                $dayResults = $monthlyResults->get($dateKey, collect())->keyBy('game_slug');
                            @endphp

                            <tr>
                                <td class="date-col">
                                    {{ $date->format('d') }}
                                </td>

                                @foreach ($sectionChartGames as $chartGame)
                                    @php
                                        $singleResult = $dayResults->get($chartGame->slug);
                                        $resultValue = $singleResult->result ?? null;
                                    @endphp

                                    <td>
                                        @if ($resultValue !== null && $resultValue !== '')
                                            {{ formatGameResult($resultValue) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (!$loop->last)
                <div style="height:25px;"></div>
            @endif
        @endforeach

        {{-- SEO Content same rakha gaya hai --}}
        <section class="a7-content-section" aria-label="A7 Satta Information">
            <div class="a7-container">
              {{-- SEO Content --}}
       
       
        <section class="a7-content-section" aria-label="A7 Satta Information">
            <div class="a7-container">
                <h2 class="a7-title">Play A7 Satta King Result Today and Get Fast & Accurate A7 Satta Updates</h2>

                <div class="a7-content-box">
                    <p>
                        A7 Satta has gained a significant following among fans for its number-based games.
                        Thousands of users log onto websites day in and day out to check the A7 Satta result
                        for the latest outcomes. Be it satta king a7 or any other, swift and updated information
                        is very important.
                    </p>

                    <p>
                        This website has been created to provide timely updates on A7 Satta results and save users
                        from unnecessary delays. We work to provide clear, structured, and reliable data that is
                        accessible to anyone at any time.
                    </p>

                    <p>
                        Our platform is ideal for those who want to access results and historical charts related to
                        the game updates quickly and conveniently in one location.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>Introduction to A7 Satta</h2>
                    <p>
                        A7 Satta has become one of the most recognized names in the number-based gaming community.
                        For users who want quick access to results, this platform provides everything needed in a
                        simple and organized manner.
                    </p>

                    <p>
                        The A7 Satta system operates daily, with results being declared at scheduled times that users
                        have come to rely on. Thousands of people visit our website every day to check the latest
                        updates of satta a7 results.
                    </p>

                    <p>
                        Our website is designed in such a way that it is easy to use, and the interface is simple
                        to understand.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>Why Choose A7 Satta?</h2>
                    <p>
                        Choosing the right platform to check results is important for your experience. Our website
                        is a reliable source for satta king a7 updates. Speed is our number one priority.
                    </p>

                    <p>
                        People do not want to wait by refreshing pages again and again or using slow websites.
                        Our website provides fast A7 Satta King results within minutes of the official announcement.
                    </p>

                    <p>
                        Reliability is what makes us different. Our website is checked and updated every day for
                        accuracy. We do not provide old information or results that are not true.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>What is A7 Satta and How Does It Work?</h2>
                    <p>
                        A7 Satta is one of the most popular number games in the Satta King A7 series. A7 is the name
                        of a particular version or market where people choose numbers and then wait for the final result.
                    </p>

                    <p>
                        The result of A7 Satta is declared every day, mostly on a fixed schedule. People select their
                        numbers, and once the final result is announced, they can check the result on authentic websites.
                    </p>

                    <p>
                        Regular followers of satta a7 results understand that it is important to check results from
                        trusted and authentic sources.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>Advantages of Using A7 Satta Website</h2>
                    <p>
                        There are many benefits of using the right website to check results. Our platform is created
                        to make result checking simple, fast, and user-friendly.
                    </p>

                    <ul>
                        <li><strong>Instant Access to Results:</strong> Once results are declared officially, they are available on our website quickly.</li>
                        <li><strong>Mobile Friendly Experience:</strong> You can check Satta King A7 result from any smartphone, tablet, or computer.</li>
                        <li><strong>Simple and Clean Design:</strong> The website layout is organized so users can find information easily.</li>
                        <li><strong>No Registration Required:</strong> You do not need to register or submit personal details to check results.</li>
                        <li><strong>Fast and Reliable Updates:</strong> Results and charts are updated regularly for a better user experience.</li>
                    </ul>
                </div>

                <div class="a7-content-box">
                    <h2>A7 Satta King Result Latest Update</h2>
                    <p>
                        The most important thing for users is to get the latest update. Our website is designed to
                        display the result of Satta King A7 as soon as it is declared.
                    </p>

                    <p>
                        The latest update page clearly shows today’s result so users can check information without
                        confusion or delay.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>Where Can I Check A7 Satta Result Online?</h2>
                    <p>
                        It can be challenging to find a trustworthy website to check results online because there are
                        many options available. Our website is a convenient platform to check the Satta A7 result
                        without complications.
                    </p>

                    <p>
                        The homepage allows users to view the latest result easily, with quick access to previous
                        results and charts. You do not have to register or fill in any personal details.
                    </p>

                    <p>
                        The website is easily accessible from smartphones, tablets, and desktops. You can check the
                        Play Bazaar A7 result with just a few clicks.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>How to Read and Interpret A7 Satta King Result Charts</h2>
                    <p>
                        Charts help users analyze previous outcomes and understand past records. The A7 Satta King
                        chart displays previous results in a simple format, allowing users to check outcomes for
                        different days, weeks, or months.
                    </p>

                    <p>
                        Reading the chart is simple. Usually, each row shows one date and its result. From the chart,
                        users can identify numbers that have appeared previously.
                    </p>

                    <p>
                        Past results do not guarantee future results, but they help users understand previous data.
                        Our A7 Satta King chart is updated regularly to provide reliable historical information.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>How to Avoid Scams When Checking A7 Satta King Result Online</h2>
                    <p>
                        Cyber safety is very important when using result websites. Not all websites are trustworthy;
                        some may show inaccurate information or try to collect personal information.
                    </p>

                    <p>
                        Always use trusted websites that have a good reputation for providing accurate and reliable
                        information. Avoid suspicious links and fake prediction websites.
                    </p>

                    <p>
                        Reputable result websites do not ask users to register, provide payment information, or share
                        personal details just to view results.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>A7 Satta Chart & Old Results</h2>
                    <p>
                        The A7 Satta chart displays previous results in table format. Users can view past results
                        for weeks, months, or years in one place.
                    </p>

                    <p>
                        Old charts are useful for checking historical records and understanding how results have
                        appeared over time. Although previous results do not predict future outcomes, a properly
                        maintained chart provides useful previous data.
                    </p>
                </div>

                <div class="a7-content-box">
                    <h2>Popular Satta Games Related to A7</h2>
                    <p>
                        A7 Satta is popular, but it is also part of a larger series of Satta King games. Popular related
                        games include Disawar, Faridabad, Ghaziabad, Delhi Bazar, Shree Ganesh, and Play Bazaar A7.
                    </p>

                    <p>
                        These games are followed by many users for daily result updates. Many users check these related
                        games along with A7 Satta to stay updated with different markets.
                    </p>
                </div>

                <h2 class="a7-faq-title">Frequently Asked Questions</h2>

                <details class="a7-card">
                    <summary>What is A7 Satta?</summary>
                    <div>
                        A7 Satta is a variant of Satta King number games. Participants choose numbers and wait for the result.
                    </div>
                </details>

                <details class="a7-card">
                    <summary>Is the A7 Satta result updated daily?</summary>
                    <div>
                        Yes. A7 Satta result is updated daily as soon as the result is available.
                    </div>
                </details>

                <details class="a7-card">
                    <summary>Where can I check fast A7 Satta result?</summary>
                    <div>
                        You can check the fast A7 Satta result on our website with real-time updates.
                    </div>
                </details>

                <details class="a7-card">
                    <summary>Is old A7 Satta chart helpful?</summary>
                    <div>
                        Yes. Old A7 Satta charts help users analyze past results and understand previous trends.
                    </div>
                </details>

                <details class="a7-card">
                    <summary>How do I avoid scams while looking for A7 Satta result online?</summary>
                    <div>
                        Use reputable platforms, avoid suspicious links, and never share personal or payment details.
                    </div>
                </details>

                <details class="a7-card">
                    <summary>How do I read the A7 Satta King result charts?</summary>
                    <div>
                        Each chart row shows a date and its result. You can check previous results according to dates.
                    </div>
                </details>
            </div>
        </section>
            </div>
        </section>

       <section class="a7-record-section" aria-label="Game 2026 Record Chart">
    @forelse($chartGames as $game)
        <div class="a7-record-heading">
            SATTA RECORD CHART
        </div>

        <div class="a7-record-link-box">
            <a href="{{ url('records/' . $game->slug) }}">
                SATTA RECORD CHART {{ $game->name }}
            </a>
        </div>

        <div class="a7-record-link-box">
            <a href="{{ url('records/' . $game->slug) }}">
                SATTA RECORD CHART 2026
            </a>
        </div>
    @empty
        <div style="background:#fff; padding:20px; text-align:center;">
            No record chart found.
        </div>
    @endforelse
</section>
    </div>
@endsection

























  

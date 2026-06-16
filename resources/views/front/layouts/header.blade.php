<div class="drag text-center">
    <span style="margin-top:4px; margin-bottom:4px; color:#000; font-size:15px; text-align:center; display:block;">
        A7-SATTAFAST | SATTA RESULT | SATTA CHART
    </span>

    <br>

    <a href="/">
        <img loading="lazy"
             src="{{ url('A1.png') }}"
             class="logo"
             alt="A7 SattaFast - Satta King Fast Result Site 2026">
    </a>

    <br>
</div>

<div style="background-color:#000; margin:2px; padding:10px; border-radius:10px; text-align:center;">

    <h1 style="color:yellow; font-size:20px; font-weight:bold;">
        A7 Satta King Result Today – A7 Satta Fast Live Update
    </h1>

    <p style="color:#FFF;">
        हाँ भाई सबसे पहले खबर यहीं आती है, रिफ्रेश रिजल्ट करो और देखो
    </p>

    <div class="datetime">

        <div style="color:white; font-weight:bold;" id="clockbox">
            {{ now('Asia/Kolkata')->format('d F Y | h:i A') }}
        </div>

        @php
            if (! function_exists('headerResultFormat')) {
                function headerResultFormat($value) {
                    if ($value === null || $value === '') {
                        return 'XX';
                    }

                    return is_numeric($value) && (int) $value <= 9
                        ? str_pad($value, 2, '0', STR_PAD_LEFT)
                        : $value;
                }
            }

            $liveGames = collect($headerGames ?? []);
            $nextGame = $liveGames->first();
        @endphp

        <div class="livegame">
            <p style="margin:0; text-transform:uppercase;">
                {{-- NEXT GAME : {{ $nextGame ? strtoupper($nextGame->name) : 'NA' }} --}}
            </p>
        </div>

        {{-- <div class="open" style="text-align:center; justify-content:center; display:flex;">
            <div class="loading"></div>
        </div> --}}

        @forelse($liveGames as $game)

            <div class="gname">
                <div class="livegame">
                    <p style="margin:0; text-transform:uppercase;">
                        {{ strtoupper($game->name) }}
                    </p>
                </div>
            </div>

            {{-- <div class="open">
                @if(
                    !empty($game->todayResult)
                    && ($game->todayResult->status ?? null) === 'declared'
                    && !empty($game->todayResult->result)
                )
                    <p style="margin:0; text-transform:uppercase;">
                        {{ headerResultFormat($game->todayResult->result) }}
                    </p>
                @else
                    <p style="margin:0;">
                        <strong class="waitimg">
                            <img class="lazy"
                                 src="{{ asset('tamplate/admin/upimages/d.gif') }}"
                                 alt="waiting"
                                 width="40"
                                 height="40">
                        </strong>
                    </p>
                @endif
            </div> --}}



            <div class="open header-result-visible">
    @if(
        !empty($game->todayResult)
        && in_array(($game->todayResult->status ?? null), ['declared', 'published'])
        && ($game->todayResult->result ?? null) !== null
        && ($game->todayResult->result ?? '') !== ''
    )
        <p>
            {{ headerResultFormat($game->todayResult->result) }}
        </p>
    @else
        <p>
            <strong class="waitimg">
                <img class="lazy"
                     src="{{ asset('tamplate/admin/upimages/d.gif') }}"
                     alt="waiting"
                     width="40"
                     height="40">
            </strong>
        </p>
    @endif
</div>

        @empty
            <div class="gname">
                <div class="livegame">
                    <p style="margin:0; text-transform:uppercase;">No Games Found</p>
                </div>
            </div>
        @endforelse

    </div>
</div>






{{-- Top Advertisement --}}
@php
    $advertisment = \App\Models\Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->first();
@endphp

@if($advertisment)
    <section class="rv-ad-wrap">
        <div class="rv-ad-box">
            @if($advertisment->content)
                <div>
                    {!! $advertisment->content !!}
                </div>
            @endif

            @if($advertisment->image)
                <a href="{{ $advertisment->link ?? '#' }}" target="_blank" style="text-decoration:none;">
                    <span class="rv-ad-img">
                        <img src="{{ asset('storage/' . $advertisment->image) }}"
                             alt="{{ $advertisment->title ?? 'Advertisement' }}">
                    </span>
                </a>
            @endif
        </div>
    </section>
@else
    <section class="rv-ad-wrap">
        <div class="rv-ad-box">
            <h2 class="rv-ad-title">नमस्कार साथियों</h2>

            <p>
                सीधा कंपनी खाईवाल के पास गेम प्ले करे<br>
                बिंदास 1001% पेमेंट की गारंटी के साथ<br>
                आपका अपना भाई
            </p>

            <h2 class="rv-ad-name">S.K Bhai</h2>

            <span class="rv-ad-img">
                <img src="{{ asset('Wp.png') }}" alt="S.K Bhai">
            </span>
        </div>
    </section>
@endif


<style>
    .loading {
        border-radius: 50%;
        background-color: rgba(0,255,255,0);
        width: 25px;
        height: 25px;
        border: 20px solid rgba(255,0,0,0.325);
        border-top: 20px solid rgb(255,0,0);
        animation: rotate 2s infinite;
    }

    @keyframes rotate {
        100% {
            rotate: 360deg;
        }
    }



    .header-result-visible,
.header-result-visible p {
    color: #ffffff !important;
    opacity: 1 !important;
    visibility: visible !important;
    font-size: 36px !important;
    font-weight: 900 !important;
    line-height: 1.1 !important;
    text-align: center !important;
    margin: 0 !important;
    text-transform: uppercase !important;
}

.header-result-visible {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-height: 46px !important;
    margin: 4px 0 14px !important;
}

.header-result-visible p {
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.45);
}
</style>
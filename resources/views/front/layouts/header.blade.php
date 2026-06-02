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

            <div class="open">
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



{{-- <div class="addb" style="background:#e9e9e9; padding:20px; margin-top:10px; text-align:center; border:2px solid #00008b; border-radius:15px;">

    <h2 style="margin:0; font-size:22px; font-weight:bold;">
        नमस्कार साथियों
    </h2>

    <p style="margin-top:10px; font-size:20px; font-weight:600; line-height:1.6;">
        सीधा कंपनी खाईवाल के पास गेम प्ले करे
        बिंदास 1001% पेमेंट की गारंटी के साथ
        आपका अपना भाई
    </p>

    <h2 style="margin-top:25px; font-size:28px; font-weight:bold;">
        S.K Bhai
    </h2>

    <img src="{{ asset('Wp.png') }}"
         alt="S.K Bhai"
         style="margin-top:10px; max-width:120px; height:auto;">

</div> --}}



@php
    $advertisment = \App\Models\Advertisement::where('is_active', true)
        ->where('position', 'top')
        ->latest()
        ->first();
@endphp

@if($advertisment)
    <div class="addb" style="background:#e9e9e9; padding:20px; margin-top:10px; text-align:center; border:2px solid #00008b; border-radius:15px;">

        {{-- @if($advertisment->title)
            <h2 style="margin:0; font-size:22px; font-weight:bold;">
                {{ $advertisment->title }}
            </h2>
        @endif --}}

        @if($advertisment->content)
            <div style="margin-top:10px; font-size:20px; font-weight:600; line-height:1.6;">
                {!! $advertisment->content !!}
            </div>
        @endif

        @if($advertisment->image)
            <a href="{{ $advertisment->link ?? '#' }}" target="_blank">
                <img src="{{ asset('storage/' . $advertisment->image) }}"
                     alt="{{ $advertisment->title ?? 'Advertisement' }}"
                     style="margin-top:10px; max-width:120px; height:auto;">
            </a>
        @endif

    </div>
@else
    <div class="addb" style="background:#e9e9e9; padding:20px; margin-top:10px; text-align:center; border:2px solid #00008b; border-radius:15px;">

        <h2 style="margin:0; font-size:22px; font-weight:bold;">
            नमस्कार साथियों
        </h2>

        <p style="margin-top:10px; font-size:20px; font-weight:600; line-height:1.6;">
            सीधा कंपनी खाईवाल के पास गेम प्ले करे
            बिंदास 1001% पेमेंट की गारंटी के साथ
            आपका अपना भाई
        </p>

        <h2 style="margin-top:25px; font-size:28px; font-weight:bold;">
            S.K Bhai
        </h2>

        <img src="{{ asset('Wp.png') }}"
             alt="S.K Bhai"
             style="margin-top:10px; max-width:120px; height:auto;">

    </div>
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
</style>
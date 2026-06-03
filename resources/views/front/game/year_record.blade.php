@extends('front.layouts.app', [
    'seo' => $seo ?? null,
])

@section('content')
    @php
        if (!function_exists('formatGameResult')) {
            function formatGameResult($value)
            {
                if ($value === null || $value === '') {
                    return '-';
                }

                return is_numeric($value) && (int) $value <= 9 ? str_pad($value, 2, '0', STR_PAD_LEFT) : $value;
            }
        }
    @endphp

    <div class="king-heading">
        <span
            style="font-weight:bold;text-align:center;margin-top:10px;background-color:#F5004F;border:2px solid #fff;padding:7px;color:#FFF;font-size:24px!important;display:flex;justify-content:center;">
            {{ strtoupper($game->name) }} SATTA RECORD CHART {{ $year }}
        </span>
    </div>

    @php
        $resultsByDate = $results->filter(fn($item) => !empty($item->result_date))->keyBy(function ($item) {
            return \Carbon\Carbon::parse($item->result_date)->format('Y-m-d');
        });

        $startDate = \Carbon\Carbon::create($year, 1, 1, 0, 0, 0, 'Asia/Kolkata');
        $endDate = \Carbon\Carbon::create($year, 12, 31, 0, 0, 0, 'Asia/Kolkata');
        $dates = \Carbon\CarbonPeriod::create($startDate, $endDate);
    @endphp

    <div class="table-responsive" style="overflow-x:scroll;">
        <table width="100%" class="month_result_table rtable" border="1" cellspacing="0" cellpadding="0">

            <tr>
                <td
                    style="font-size:14px;white-space:nowrap;background-color:#cc4c1a;color:#fff;text-align:center;padding:6px 8px;text-transform:uppercase;">
                    DATE
                </td>
                <td
                    style="font-size:14px;white-space:nowrap;background-color:#cc4c1a;color:#fff;text-align:center;padding:6px 8px;text-transform:uppercase;">
                    {{ strtoupper($game->name) }}
                </td>
            </tr>

            @foreach ($dates as $date)
                @php
                    $dateKey = $date->format('Y-m-d');
                    $record = $resultsByDate->get($dateKey);

                    $resultValue = $record->result ?? null;
                    $resultStatus = $record->status ?? 'waiting';
                @endphp

                <tr>
                    <td style="font-size:18px;background-color:#3333ff;color:#fff;text-align:center;font-weight:bold;">
                        {{ $date->format('d-m-Y') }}
                    </td>

                    <td
                        style="font-size:15px;font-weight:bold;background-color:#fff;padding:6px 2px 7px 2px;text-align:center;">
                        @if ($resultStatus === 'declared' && $resultValue !== null && $resultValue !== '')
                            {{ formatGameResult($resultValue) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>
    </div>



    @if(isset($contentBlocks) && $contentBlocks->count())
    <div class="mt-8 space-y-6">
        @foreach($contentBlocks as $block)
            <div class="rounded-xl border bg-white p-5 shadow-sm">
                @if($block->title)
                    <h2 class="mb-3 text-xl font-bold">
                        {{ $block->title }}
                    </h2>
                @endif

                <div class="prose max-w-none">
                    {!! $block->content !!}
                </div>
            </div>
        @endforeach
    </div>
@endif


    <div style="text-align:center;margin:20px 0;">
        <a href="{{ url('/chart') }}" style="font-weight:bold;color:#000;">
            BACK TO CHART
        </a>
    </div>
@endsection

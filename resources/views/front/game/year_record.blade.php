

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
        <span style="font-weight:bold;text-align:center;margin-top:10px;background-color:#F5004F;border:2px solid #fff;padding:7px;color:#FFF;font-size:24px!important;display:flex;justify-content:center;">
            {{ strtoupper($game->name) }} SATTA RECORD CHART {{ $year }}
        </span>
    </div>

    @php
        $months = [
            1  => 'January',   2  => 'February', 3  => 'March',
            4  => 'April',     5  => 'May',      6  => 'June',
            7  => 'July',      8  => 'August',   9  => 'September',
            10 => 'October',   11 => 'November', 12 => 'December',
        ];

        // Build lookup: day => month => result
        $grid = [];
        foreach ($results as $result) {
            if (empty($result->result_date)) continue;
            $date  = \Carbon\Carbon::parse($result->result_date);
            $day   = (int) $date->format('d');
            $month = (int) $date->format('m');
            if ($result->status === 'declared' && $result->result !== null && $result->result !== '') {
                $grid[$day][$month] = formatGameResult($result->result);
            } else {
                $grid[$day][$month] = '-';
            }
        }
    @endphp

    <div class="table-responsive" style="overflow-x:scroll;">
        <table width="100%" class="month_result_table rtable record-chart-table" border="1" cellspacing="0" cellpadding="0">

            {{-- Header row --}}
            <tr>
                <td class="chart-year-cell">{{ $year }}</td>
                @foreach($months as $num => $name)
                    <td class="chart-month-cell">{{ $name }}</td>
                @endforeach
            </tr>

            {{-- Day rows --}}
            @for($day = 1; $day <= 31; $day++)
                <tr>
                    <td class="chart-day-cell">
                        {{ str_pad($day, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    @foreach($months as $num => $name)
                        @php
                            $daysInMonth = \Carbon\Carbon::create($year, $num, 1)->daysInMonth;
                            $cellValue   = ($day <= $daysInMonth) ? ($grid[$day][$num] ?? '-') : '-';
                        @endphp
                        <td class="chart-result-cell {{ $cellValue !== '-' ? 'has-result' : '' }}">
                            {{ $cellValue }}
                        </td>
                    @endforeach
                </tr>
            @endfor

        </table>
    </div>

    {{-- @if(isset($contentBlocks) && $contentBlocks->count())
        <div class="mt-8 space-y-6">
            @foreach($contentBlocks as $block)
                <div class="rounded-xl border bg-white p-5 shadow-sm">
                    @if($block->title)
                        <h2 class="mb-3 text-xl font-bold">
                            {{ $block->title }}
                        </h2>
                    @endif
                    <div class="content-block-content">
                        {!! $block->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif --}}


@if(isset($contentBlocks) && $contentBlocks->count())
    <!-- Wrapper Container: Width ko manage aur center karne ke liye -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="space-y-6">
            @foreach($contentBlocks as $block)
                <div class="rounded-xl border bg-white p-5 shadow-sm">
                    @if($block->title)
                        <h2 class="mb-3 text-xl font-bold text-gray-800">
                            {{ $block->title }}
                        </h2>
                    @endif
                    <div class="content-block-content text-gray-600">
                        {!! $block->content !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif


    <div style="text-align:center;margin:20px 0;">
        <a href="{{ url('/chart') }}" style="font-weight:bold;color:#000;">
            BACK TO CHART
        </a>
    </div>

    <style>
        .record-chart-table {
            font-family: Arial, sans-serif;
            font-size: 14px;
            border-collapse: collapse;
        }

        /* Header - year cell */
        .record-chart-table .chart-year-cell {
            background-color: #cc4c1a;
            color: #fff;
            font-weight: 700;
            text-align: center;
            padding: 8px 6px;
            white-space: nowrap;
            min-width: 52px;
            font-size: 14px;
            text-transform: uppercase;
        }

        /* Header - month cells */
        .record-chart-table .chart-month-cell {
            background-color: #cc4c1a;
            color: #fff;
            font-weight: 700;
            text-align: center;
            padding: 8px 6px;
            white-space: nowrap;
            font-size: 14px;
            text-transform: uppercase;
        }

        /* Day column cells */
        .record-chart-table .chart-day-cell {
            background-color: #3333ff;
            color: #fff;
            font-weight: 700;
            text-align: center;
            padding: 6px 4px;
            font-size: 15px;
            min-width: 52px;
        }

        /* Result cells */
        .record-chart-table .chart-result-cell {
            background-color: #ffffff;
            color: #222;
            text-align: center;
            padding: 6px 4px;
            font-size: 15px;
            font-weight: 600;
            min-width: 60px;
        }

        /* Highlight declared results */
        .record-chart-table .chart-result-cell.has-result {
            color: #111;
            font-weight: 700;
        }

        /* Alternate row tint */
        .record-chart-table tr:nth-child(even) .chart-result-cell {
            background-color: #fff5f5;
        }

        @media (max-width: 768px) {
            .record-chart-table {
                font-size: 11px;
            }
            .record-chart-table td {
                padding: 3px 2px;
                min-width: 32px;
            }
        }
    </style>

@endsection




















{{-- @extends('front.layouts.app', [
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

            <div class="content-block-content">
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
@endsection --}}

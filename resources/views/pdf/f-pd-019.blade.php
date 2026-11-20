{{--
    F-PD-019 ใบติดตามการปฏิบัติงานติดตั้ง (ฉบับที่ 2) — หน้าแรก 10 ครั้ง, ส่วนที่ 2 หน้าละ 17 ครั้ง

    $data = [
        'page' => '', 'pages' => '',
        'doc_no' => '', 'doc_date' => null, 'unit' => 'sampran' | 'lamlukka' | 'banbueng',
        'employer' => '', 'address' => '', 'project_name' => '', 'location' => '',
        'contact_name' => '', 'phone' => '', 'fax' => '',
        'field_section_head' => '', 'received_date' => null,
        // แผนงานและรายละเอียดการติดตั้ง (2 แถว) — วันที่ string หรือ Carbon
        'plan_dates' => [
            ['prepared' => null, 'dept_approved' => null, 'sent_customer' => null, 'customer_approved' => null, 'summarized' => null],
        ],
        'plan_summary' => "หลายบรรทัด",
    ];
    $rounds = [
        ['zone' => '', 'area' => '', 'approved_date' => null, 'postponed_date' => null, 'actual_date' => null,
         'confirm_48h' => '', 'done' => true | false | null, 'remark' => ''],
    ];
    $showPart2 = true;   // แสดงหน้า "ส่วนที่ 2" แม้ไม่มีข้อมูลเกิน 10 ครั้ง
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-019 ฉบับที่ : 2')
@section('title', 'F-PD-019 ใบติดตามการปฏิบัติงานติดตั้ง')
@section('font-size', '14')

@php
    $data = $data ?? [];
    $rounds = collect($rounds ?? [])->values();
    $first = $rounds->take(10)->pad(10, null);
    $rest = $rounds->slice(10)->values();
    $restPages = $rest->isEmpty()
        ? (($showPart2 ?? true) ? collect([collect()]) : collect())
        : $rest->chunk(17);
    $unit = $data['unit'] ?? null;
    $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('d/m/Y') : '';
    $planDates = collect($data['plan_dates'] ?? [])->pad(2, []);
    $summary = collect(preg_split('/\r\n|\r|\n/', (string) ($data['plan_summary'] ?? '')))->filter(fn ($l) => $l !== '')->values();
    $summaryFirst = $summary->shift() ?? '';
@endphp

@section('styles')
    .rounds td { height: 40px; padding-top: 0; padding-bottom: 0; }
    .rounds td.res { text-align: left; padding-left: 2px; font-size: 11.5pt; }
@endsection

@section('content')
<div style="border:1px solid #000; padding:2px 6px 4px 6px;">
    <div class="title">ใบติดตามการปฏิบัติงานติดตั้ง</div>
    <table><tr>
        <td style="padding:0;">บริษัท วงศ์ชัย จำกัด</td>
        <td class="right" style="padding:0;">หน้าที่ {{ $data['page'] ?? '......' }}/{{ $data['pages'] ?? '......' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l">เลขที่</td><td class="vc" style="width:24mm;">{{ $data['doc_no'] ?? '' }}</td>
        <td class="l">&nbsp; ลงวันที่</td><td class="l">@include('pdf.partials.date', ['value' => $data['doc_date'] ?? null])</td>
        <td class="l">&nbsp; หน่วยงาน</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $unit === 'sampran']) สามพราน</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $unit === 'lamlukka']) ลำลูกกา</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $unit === 'banbueng']) บ้านบึง</td>
        <td></td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l" style="width:22mm;">ผู้ว่าจ้าง</td><td class="v" style="width:34%;">{{ $data['employer'] ?? '' }}</td>
        <td class="l">&nbsp; ที่อยู่</td><td class="v">{{ $data['address'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l" style="width:22mm;">ชื่อโครงการ</td><td class="v" style="width:34%;">{{ $data['project_name'] ?? '' }}</td>
        <td class="l">&nbsp; ที่ตั้ง</td><td class="v">{{ $data['location'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l" style="width:22mm;">ชื่อผู้ติดต่อ</td><td class="v" style="width:34%;">{{ $data['contact_name'] ?? '' }}</td>
        <td class="l">&nbsp; เบอร์โทร</td><td class="v">{{ $data['phone'] ?? '' }}</td>
        <td class="l">&nbsp; Fax</td><td class="v" style="width:22%;">{{ $data['fax'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l">หัวหน้าส่วนปฏิบัติการสนาม</td><td class="v">{{ $data['field_section_head'] ?? '' }}</td>
        <td class="l">&nbsp; รับงานวันที่</td><td class="l">@include('pdf.partials.date', ['value' => $data['received_date'] ?? null])</td>
    </tr></table>
</div>

<div style="border:1px solid #000; border-top:none; padding:1px 6px;">แผนงานและรายละเอียดการติดตั้ง</div>
<table class="grid" style="border:1px solid #000; border-top:none;">
    <tr>
        <th style="width:20%;">วันที่จัดทำ</th>
        <th style="width:20%;">วันที่หัวหน้าแผนกอนุมัติ</th>
        <th style="width:20%;">วันที่ส่งให้ลูกค้า</th>
        <th style="width:20%;">วันที่ได้รับอนุมัติจากลูกค้า</th>
        <th style="width:20%;">วันที่สรุปโดยแผนก</th>
    </tr>
    @foreach ($planDates as $pd)
        <tr class="row-h">
            @foreach (['prepared', 'dept_approved', 'sent_customer', 'customer_approved', 'summarized'] as $k)
                <td class="center">{{ $fmt($pd[$k] ?? null) }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
<div style="border:1px solid #000; border-top:none; padding:14px 6px 4px 6px;">
    <table class="ln"><tr><td class="l">สรุปแผนงาน</td><td class="v">{{ $summaryFirst }}</td></tr></table>
    <div class="line">{{ $summary->implode(' ') }}&nbsp;</div>
</div>

@include('pdf.partials.f-pd-019-rounds', ['list' => $first, 'start' => 1, 'withNumbers' => true, 'topBorder' => false])

@foreach ($restPages as $p => $chunk)
    <div class="page-break"></div>
    @include('pdf.partials.f-pd-019-rounds', [
        'list' => collect($chunk)->values()->pad(17, null),
        'start' => 11 + $p * 17,
        'withNumbers' => $rest->isNotEmpty(),
        'topBorder' => true,
    ])
    <div class="small">(ส่วนที่ 2)</div>
@endforeach
@endsection

{{--
    F-PD-004 แผนงานการส่งมอบและวางแผนการผลิตผลิตภัณฑ์ประจำสัปดาห์ (ฉบับที่ 3) — A4 แนวนอน
    เรียกใช้: Pdf::loadView('pdf.f-pd-004', [...])->setPaper('a4', 'landscape')

    $data = [
        'branch' => '', 'plant' => '',
        'plan_type' => 'delivery' | 'production',   // ติ๊ก แผนการส่งมอบ / แผนการผลิต
        'page' => 1, 'pages' => 1,
        'start_date' => '2026-07-02', 'end_date' => '2026-08-05',  // ใช้สร้างหัวคอลัมน์วัน (35 วัน)
        'revision' => '',                           // เปลี่ยนแปลงครั้งที่
        'location_type' => 'zone' | 'lane',          // ติ๊ก โซน/ชั้น หรือ เลน
        'prepared_by' => '', 'prepared_date' => null, 'prepared_role' => 'field_section' | 'plant_dept',
        'checked_by' => '',  'checked_date' => null,
        'approved_by' => '', 'approved_date' => null, 'approved_role' => 'field_manager' | 'plant_manager',
    ];
    $items = [
        ['project_name' => '', 'production_no' => '', 'thickness' => '', 'wire' => '', 'load' => '',
         'area' => '', 'location' => '', 'days' => [1 => 'x', 5 => '120', ...],  // index 1..35
         'remark' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-004 ฉบับที่ 3')
@section('title', 'F-PD-004 แผนงานการส่งมอบและวางแผนการผลิต')
@section('orientation', 'landscape')
@section('margin', '8mm 8mm 8mm 8mm')
@section('font-size', '11.5')
@section('row-height', '17px')

@php
    $data = $data ?? [];
    $items = collect($items ?? []);
    $minRows = $minRows ?? 20;
    $rows = $items->pad(max($minRows, $items->count()), null);
    $dayCount = 35;

    $thDays = ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'];
    $start = ! empty($data['start_date']) ? \Illuminate\Support\Carbon::parse($data['start_date']) : null;
    $days = [];
    for ($i = 0; $i < $dayCount; $i++) {
        if ($start) {
            $d = $start->copy()->addDays($i);
            $days[$i + 1] = ['label' => $thDays[$d->dayOfWeek], 'num' => $d->day, 'sunday' => $d->isSunday()];
        } else {
            // ค่าเริ่มต้นตามฟอร์มต้นฉบับ เริ่มวันศุกร์
            $w = ($i + 5) % 7;
            $days[$i + 1] = ['label' => $thDays[$w], 'num' => '', 'sunday' => $w === 0];
        }
    }
    $thMonths = [1 => 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    $end = ! empty($data['end_date']) ? \Illuminate\Support\Carbon::parse($data['end_date']) : null;
@endphp

@section('styles')
    .day { width: 13px; padding: 0; text-align: center; }
    .sign td { vertical-align: top; }
    thead th { white-space: nowrap; }
    .sign td.l { width: 1%; }
@endsection

@section('content')
<div class="center bold large">แผนงานการส่งมอบและวางแผนการผลิตผลิตภัณฑ์ประจำสัปดาห์</div>
<div class="center bold">SCHEDULE FOR DELIVERY AND PRODUCTION PLANING OF HOLLOW CORE SLAB</div>

<table class="grid" style="border:1.2px solid #000; margin-top:3px;">
    <thead>
        <tr>
            <td colspan="{{ 9 + $dayCount }}" style="border-bottom:none;">
                <table class="ln">
                    <tr>
                        <td class="l bold">บริษัท วงศ์ชัย จำกัด &nbsp; สาขา</td>
                        <td class="v" style="width:18%;">{{ $data['branch'] ?? '' }}</td>
                        <td class="l bold">&nbsp; โรงผลิต</td>
                        <td class="v" style="width:18%;">{{ $data['plant'] ?? '' }}</td>
                        <td style="width:20%;"></td>
                        <td class="l bold">@include('pdf.partials.cb', ['checked' => ($data['plan_type'] ?? null) === 'delivery']) แผนการส่งมอบ</td>
                        <td class="l bold">@include('pdf.partials.cb', ['checked' => ($data['plan_type'] ?? null) === 'production']) แผนการผลิต</td>
                        <td style="width:10%;"></td>
                        <td class="l bold">หน้าที่ &nbsp; {{ $data['page'] ?? 1 }} / {{ $data['pages'] ?? 1 }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th rowspan="3" style="width:3%;" class="bold">ลำดับ</th>
            <th rowspan="3" style="width:14%;" class="bold">ชื่อโครงการ</th>
            <th rowspan="2" style="width:5%; border-bottom:none;" class="bold">Production</th>
            <th rowspan="2" style="width:3%; border-bottom:none;" class="bold">หนา</th>
            <th rowspan="2" style="width:6%; border-bottom:none;" class="bold">ลวด</th>
            <th rowspan="2" style="width:5%; border-bottom:none;" class="bold">LL + SDL</th>
            <th rowspan="2" style="width:5%; border-bottom:none;" class="bold">พื้นที่</th>
            <th rowspan="2" style="width:5%; text-align:left; border-bottom:none;" class="bold">@include('pdf.partials.cb', ['checked' => ($data['location_type'] ?? null) === 'zone'])โซน/ชั้น</th>
            <th colspan="{{ $dayCount }}" class="bold" style="text-align:left;">
                ระยะเวลาที่แผนควบคุมตั้งแต่ วันที่ &nbsp; {{ $start?->format('d') }} &nbsp; {{ $start ? $thMonths[$start->month] : '' }}
                &nbsp;&nbsp; ถึง &nbsp; {{ $end?->format('d') }} &nbsp; เดือน &nbsp; {{ $end ? $thMonths[$end->month] : '' }}
                &nbsp;&nbsp; ค.ศ. &nbsp; {{ ($end ?? $start)?->year ?? '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp; เปลี่ยนแปลงครั้งที่ : {{ $data['revision'] ?? '' }}
            </th>
            <th rowspan="3" style="width:7%;" class="bold">หมายเหตุ</th>
        </tr>
        <tr>
            @foreach ($days as $d)
                <th class="day bold {{ $d['sunday'] ? 'shade' : '' }}">{{ $d['label'] }}</th>
            @endforeach
        </tr>
        <tr>
            <th class="bold" style="border-top:none;">No.</th>
            <th class="bold" style="border-top:none;">(cm.)</th>
            <th class="bold" style="border-top:none;">(inch / mm.)</th>
            <th class="bold" style="border-top:none;">(kg./sq.m.)</th>
            <th class="bold" style="border-top:none;">(sq.m.)</th>
            <th class="bold" style="text-align:left; border-top:none;">@include('pdf.partials.cb', ['checked' => ($data['location_type'] ?? null) === 'lane'])เลน</th>
            @foreach ($days as $d)
                <th class="day {{ $d['sunday'] ? 'shade' : '' }}">{{ $d['num'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $i => $row)
            <tr class="row-h">
                <td class="center">{{ $row ? $i + 1 : '' }}</td>
                <td>{{ $row['project_name'] ?? '' }}</td>
                <td class="center">{{ $row['production_no'] ?? '' }}</td>
                <td class="center">{{ $row['thickness'] ?? '' }}</td>
                <td class="center">{{ $row['wire'] ?? '' }}</td>
                <td class="center">{{ $row['load'] ?? '' }}</td>
                <td class="center">{{ $row['area'] ?? '' }}</td>
                <td class="center">{{ $row['location'] ?? '' }}</td>
                @foreach ($days as $n => $d)
                    <td class="day {{ $d['sunday'] ? 'shade' : '' }}">{{ $row['days'][$n] ?? '' }}</td>
                @endforeach
                <td>{{ $row['remark'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="sign" style="border:1.2px solid #000; border-top:none;">
    <tr>
        <td style="width:34%; border-right:0.7px solid #000; padding:8px 8px 4px 8px;">
            <table class="ln"><tr><td class="l bold">ผู้จัดทำ :</td><td class="vc">{{ $data['prepared_by'] ?? '' }}</td><td class="gap"></td><td class="l" style="width:22mm;">@include('pdf.partials.date', ['value' => $data['prepared_date'] ?? null])</td></tr></table>
            <div style="padding-left:30mm; margin-top:4px;">
                <div>@include('pdf.partials.cb', ['checked' => ($data['prepared_role'] ?? null) === 'field_section']) หัวหน้าส่วนปฏิบัติการสนาม</div>
                <div>@include('pdf.partials.cb', ['checked' => ($data['prepared_role'] ?? null) === 'plant_dept']) หัวหน้าแผนกปฏิบัติการโรงงาน</div>
            </div>
        </td>
        <td style="width:33%; border-right:0.7px solid #000; padding:8px 8px 4px 8px;">
            <table class="ln"><tr><td class="l bold">ผู้ตรวจสอบ</td><td class="vc">{{ $data['checked_by'] ?? '' }}</td><td class="gap"></td><td class="l" style="width:22mm;">@include('pdf.partials.date', ['value' => $data['checked_date'] ?? null])</td></tr></table>
            <div style="padding-left:25mm; margin-top:4px;">
                <div>@include('pdf.partials.cb', ['checked' => ! empty($data['checked_by'])]) หัวหน้าแผนกปฏิบัติการสนาม</div>
            </div>
        </td>
        <td style="width:33%; padding:8px 8px 4px 8px;">
            <table class="ln"><tr><td class="l bold">ผู้อนุมัติ :</td><td class="vc">{{ $data['approved_by'] ?? '' }}</td><td class="gap"></td><td class="l" style="width:22mm;">@include('pdf.partials.date', ['value' => $data['approved_date'] ?? null])</td></tr></table>
            <div style="padding-left:30mm; margin-top:4px;">
                <div>@include('pdf.partials.cb', ['checked' => ($data['approved_role'] ?? null) === 'field_manager']) ผู้จัดการฝ่ายปฏิบัติการสนาม</div>
                <div>@include('pdf.partials.cb', ['checked' => ($data['approved_role'] ?? null) === 'plant_manager']) ผู้จัดการฝ่ายโรงงาน</div>
            </div>
        </td>
    </tr>
</table>
@endsection

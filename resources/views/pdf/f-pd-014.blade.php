{{--
    F-PD-014 ใบแผนงานและรายละเอียดการติดตั้ง (ฉบับที่ 1)

    $data = [
        'date' => null, 'doc_no' => '', 'project_name' => '',
        'transport' => bool, 'transport_detail' => '',
        'install' => bool, 'install_detail' => "หลายบรรทัด",
        'work_time' => 'day' | 'night' | 'other', 'work_time_other' => '',   // 08.00–17.00 / 21.00–05.00
        'prepared_by' => '', 'prepared_date' => null, 'checked_by' => '', 'checked_date' => null,
        // ผลการพิจารณา (ลูกค้า)
        'result' => 'approved' | 'rejected' | null, 'result_note' => "หลายบรรทัด",
        'customer_name' => '', 'customer_date' => null,
        // สรุป
        'summary' => "หลายบรรทัด", 'summary_by' => '', 'summary_date' => null,
    ];
    $items = [
        ['seq' => '', 'location' => '', 'area' => '', 'round1' => '', 'round2' => '', 'round3' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-014 ฉบับที่ : 1')
@section('title', 'F-PD-014 ใบแผนงานและรายละเอียดการติดตั้ง')
@section('line-height', '6.6mm')

@php
    $data = $data ?? [];
    $items = collect($items ?? []);
    $rows = $items->pad(max(8, $items->count()), null);
    $lines = fn ($text, $min) => collect(preg_split('/\r\n|\r|\n/', (string) ($text ?? '')))
        ->filter(fn ($l) => $l !== '')->values()->pad($min, '');
    $installLines = $lines($data['install_detail'] ?? '', 3);
    $resultLines = $lines($data['result_note'] ?? '', 2);
    $summaryLines = $lines($data['summary'] ?? '', 3);
    $wt = $data['work_time'] ?? null;
@endphp

@section('styles')
    .sec { border: 1px solid #000; border-top: none; padding: 4px 10px 6px 10px; }
    .plan td { padding: 0 3px; }
    .plan td.d { border-bottom: 0.8px dotted #000; height: 6.4mm; vertical-align: bottom; }
@endsection

@section('content')
<div style="height:6mm;"></div>
<div class="sec" style="border-top:1px solid #000; padding-bottom:2px;">
    <div class="title">ใบแผนงานและรายละเอียดการติดตั้ง</div>
    <table>
        <tr>
            <td class="small" style="padding:0;">บริษัท วงศ์ชัย จำกัด</td>
            <td class="right small" style="padding:0;">วันที่ : @include('pdf.partials.date', ['value' => $data['date'] ?? null])</td>
        </tr>
    </table>
</div>

<div class="sec">
    <div class="bold">รายละเอียดการติดตั้ง</div>
    <table class="ln"><tr>
        <td class="l">เลขที่</td><td class="vc" style="width:22mm;">{{ $data['doc_no'] ?? '' }}</td>
        <td class="l" style="padding-left:12mm;">ชื่อโครงการ :</td><td class="v">{{ $data['project_name'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l" style="padding-left:4mm;">@include('pdf.partials.cb', ['checked' => ! empty($data['transport'])]) การขนส่ง</td>
        <td class="v">{{ $data['transport_detail'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l" style="padding-left:4mm;">@include('pdf.partials.cb', ['checked' => ! empty($data['install'])]) การติดตั้ง</td>
        <td class="v">{{ $installLines[0] }}</td>
    </tr></table>
    @foreach ($installLines->slice(1) as $line)
        <div class="line" style="margin-left:14mm;">{{ $line }}&nbsp;</div>
    @endforeach
    <table class="ln"><tr>
        <td class="l">เวลาในการปฏิบัติงาน</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $wt === 'day']) 08.00 – 17.00 น.</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $wt === 'night']) 21.00 – 05.00 น.</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $wt === 'other'])</td>
        <td class="v">{{ $data['work_time_other'] ?? '' }}</td>
    </tr></table>

    <div class="bold" style="margin-top:4px;">แผนงาน</div>
    <table class="plan">
        <tr>
            <td class="center" style="width:10%;">ลำดับ</td>
            <td class="center" style="width:30%;">ชั้น / โซน</td>
            <td class="center" style="width:14%;">พื้นที่ (ม<sup>2</sup>)</td>
            <td class="center" colspan="3">กำหนดการปฏิบัติงาน (โดยประมาณ)</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="center" style="width:16%;">ครั้งที่ 1</td>
            <td class="center" style="width:16%;">ครั้งที่ 2</td>
            <td class="center" style="width:14%;">ครั้งที่ 3</td>
        </tr>
        @foreach ($rows as $i => $row)
            <tr>
                <td class="d center">{{ $row['seq'] ?? ($row ? $i + 1 : '') }}</td>
                <td class="d">{{ $row['location'] ?? '' }}</td>
                <td class="d center">{{ $row['area'] ?? '' }}</td>
                <td class="d center">{{ $row['round1'] ?? '' }}</td>
                <td class="d center">{{ $row['round2'] ?? '' }}</td>
                <td class="d center">{{ $row['round3'] ?? '' }}</td>
            </tr>
        @endforeach
    </table>

    <table style="margin-top:10px;">
        <tr>
            <td style="width:50%; padding:0;">
                <table class="ln"><tr><td class="l">ผู้จัดทำ</td><td class="vc">{{ $data['prepared_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['prepared_date'] ?? null])</td></tr></table>
            </td>
            <td style="width:50%; padding:0 0 0 8px;">
                <table class="ln"><tr><td class="l">ผู้ตรวจสอบ</td><td class="vc">{{ $data['checked_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['checked_date'] ?? null])</td></tr></table>
            </td>
        </tr>
        <tr>
            <td class="center" style="padding-right:20mm;">(หัวหน้าส่วนปฏิบัติการสนาม)</td>
            <td class="center" style="padding-right:20mm;">(หัวหน้าแผนกปฏิบัติการสนาม)</td>
        </tr>
    </table>
</div>

<div class="sec">
    <table>
        <tr>
            <td class="bold" style="width:30%; padding:0;">ผลการพิจารณา</td>
            <td style="width:20%;">@include('pdf.partials.cb', ['checked' => ($data['result'] ?? null) === 'approved']) อนุมัติ</td>
            <td>@include('pdf.partials.cb', ['checked' => ($data['result'] ?? null) === 'rejected']) ไม่อนุมัติ</td>
        </tr>
    </table>
    @foreach ($resultLines as $line)
        <div class="line">{{ $line }}&nbsp;</div>
    @endforeach
    <table style="margin-top:6px;">
        <tr>
            <td style="width:25%;"></td>
            <td style="width:55%; padding:0;">
                <table class="ln"><tr><td class="l">ลูกค้าหรือตัวแทนลูกค้า</td><td class="vc">{{ $data['customer_name'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['customer_date'] ?? null])</td></tr></table>
            </td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="padding:6px 0 0 30mm;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
            <td></td>
        </tr>
    </table>
</div>

<div class="sec">
    <table class="ln"><tr><td class="l bold">สรุปแผนงานและรายละเอียดการติดตั้ง</td><td class="v">{{ $summaryLines[0] }}</td></tr></table>
    @foreach ($summaryLines->slice(1) as $line)
        <div class="line">{{ $line }}&nbsp;</div>
    @endforeach
    <table style="margin-top:12px;">
        <tr>
            <td style="width:40%;"></td>
            <td style="padding:0;">
                <table class="ln"><tr><td class="l">หัวหน้าแผนกปฏิบัติการสนาม</td><td class="vc">{{ $data['summary_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['summary_date'] ?? null])</td></tr></table>
            </td>
        </tr>
    </table>
</div>
@endsection

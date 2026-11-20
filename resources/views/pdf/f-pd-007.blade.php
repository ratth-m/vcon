{{--
    F-PD-007 ใบสั่งเปลี่ยนแปลงแผนการผลิตและการส่งมอบผลิตภัณฑ์ (ฉบับที่ 2)

    $data = [
        'date' => null,
        'ref_plan_date' => null,       // อ้างอิงวันที่อนุมัติแผนการผลิตและการส่งมอบผลิตภัณฑ์
        'reason' => "หลายบรรทัด",
        'informer' => '', 'informer_date' => null,
        'checker' => '',  'checker_date' => null,
        'approver' => '', 'approver_date' => null,
    ];
    $items = [
        ['project_name' => '', 'original' => '', 'changed' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-007 ฉบับที่ : 2')
@section('title', 'F-PD-007 ใบสั่งเปลี่ยนแปลงแผนการผลิตและการส่งมอบผลิตภัณฑ์')

@php
    $data = $data ?? [];
    $items = collect($items ?? []);
    $minRows = $minRows ?? 18;
    $rows = $items->pad(max($minRows, $items->count()), null);
    $reasonLines = collect(preg_split('/\r\n|\r|\n/', (string) ($data['reason'] ?? '')))
        ->filter(fn ($l) => $l !== '')->values();
    $firstReason = $reasonLines->shift() ?? '';
    $reasonLines = $reasonLines->pad(8, '');
    $refDate = ! empty($data['ref_plan_date']) ? \Illuminate\Support\Carbon::parse($data['ref_plan_date'])->format('d/m/Y') : '';
    $date = ! empty($data['date']) ? \Illuminate\Support\Carbon::parse($data['date'])->format('d/m/Y') : '';
@endphp

@section('styles')
    .row-h td { font-size: 13pt; }
@endsection

@section('content')
<div style="height:6mm;"></div>
<table class="box">
    <tr><td colspan="2" class="title" style="padding-top:4px;">ใบสั่งเปลี่ยนแปลงแผนการผลิตและการส่งมอบผลิตภัณฑ์</td></tr>
    <tr>
        <td class="small" style="width:40%; padding-left:6px;">บริษัท วงศ์ชัย จำกัด</td>
        <td><table class="ln" style="width:60%;"><tr><td class="l">วันที่</td><td class="vc">{{ $date }}</td></tr></table></td>
    </tr>
</table>

<div class="box" style="border-top:none; padding:6px 8px;">
    <table class="ln"><tr>
        <td class="l">อ้างอิงวันที่อนุมัติแผนการผลิตและการส่งมอบผลิตภัณฑ์ :</td>
        <td class="vc" style="width:32mm;">{{ $refDate }}</td><td></td>
    </tr></table>
    <table class="ln"><tr><td class="l">สาเหตุที่ขอเปลี่ยนแปลง :</td><td class="v">{{ $firstReason }}</td></tr></table>
    @foreach ($reasonLines as $line)
        <div class="line">{{ $line }}&nbsp;</div>
    @endforeach
    <div style="height:6px;"></div>
</div>

<table class="grid" style="border:1px solid #000; border-top:none;">
    <tr>
        <th style="width:8%;">ลำดับ</th>
        <th style="width:44%;">ชื่อโครงการ</th>
        <th style="width:24%;">รายละเอียดในแผนเดิม</th>
        <th style="width:24%;">รายละเอียดที่เปลี่ยนแปลง</th>
    </tr>
    @foreach ($rows as $i => $row)
        <tr class="row-h">
            <td class="center">{{ $row ? $i + 1 : '' }}</td>
            <td>{{ $row['project_name'] ?? '' }}</td>
            <td>{{ $row['original'] ?? '' }}</td>
            <td>{{ $row['changed'] ?? '' }}</td>
        </tr>
    @endforeach
</table>

<table class="grid" style="border:1px solid #000; border-top:none;">
    <tr>
        @foreach ([['ผู้แจ้ง', 'informer'], ['ผู้ตรวจสอบ', 'checker'], ['ผู้อนุมัติ', 'approver']] as [$label, $key])
            <td style="width:33.3%; border-top:none; vertical-align:top; padding:3px 6px 6px 6px;">
                <div>{{ $label }}</div>
                <table class="ln" style="width:80%; margin:10px auto 0 auto;"><tr><td class="vc">{{ $data[$key] ?? '' }}</td></tr></table>
                <div class="center" style="margin-top:4px;">@include('pdf.partials.date', ['value' => $data[$key.'_date'] ?? null])</div>
            </td>
        @endforeach
    </tr>
</table>
@endsection

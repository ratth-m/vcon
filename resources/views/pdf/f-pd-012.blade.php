{{--
    F-PD-012 ใบติดตามการขออนุมัติ Shop Drawing (ฉบับที่ 2)

    $items = [
        ['project_name' => '', 'shop_drawing_no' => '', 'sent_date' => null,
         'result' => 'approved' | 'rejected' | null],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-012 ฉบับที่ : 2')
@section('title', 'F-PD-012 ใบติดตามการขออนุมัติ Shop Drawing')
@section('row-height', '25px')

@php
    $items = collect($items ?? []);
    $minRows = $minRows ?? 30;
    $rows = $items->pad(max($minRows, $items->count()), null);
    $mark = '<span style="font-family: \'DejaVu Sans\';">&#10003;</span>';
@endphp

@section('styles')
    .row-h td { font-size: 13pt; }
@endsection

@section('content')
<table class="grid" style="border:1px solid #000;">
    <thead>
        <tr><td colspan="6" class="title" style="border-bottom:none; padding-top:4px;">ใบติดตามการขออนุมัติ Shop Drawing</td></tr>
        <tr><td colspan="6" style="border-top:none;">บริษัท วงศ์ชัย จำกัด</td></tr>
        <tr>
            <th rowspan="2" style="width:8%;">ลำดับ</th>
            <th rowspan="2" style="width:44%;">ชื่อโครงการ</th>
            <th rowspan="2" style="width:14%;">เลขที่ Shop<br>Drawing</th>
            <th rowspan="2" style="width:14%;">วันที่<br>ส่งของอนุมัติ</th>
            <th colspan="2">สรุปผลการพิจารณา</th>
        </tr>
        <tr>
            <th style="width:10%;">อนุมัติ</th>
            <th style="width:10%;">ไม่อนุมัติ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $i => $row)
            <tr class="row-h">
                <td class="center">{{ $row ? $i + 1 : '' }}</td>
                <td>{{ $row['project_name'] ?? '' }}</td>
                <td class="center">{{ $row['shop_drawing_no'] ?? '' }}</td>
                <td class="center">{{ ! empty($row['sent_date']) ? \Illuminate\Support\Carbon::parse($row['sent_date'])->format('d/m/Y') : '' }}</td>
                <td class="center">{!! ($row['result'] ?? null) === 'approved' ? $mark : '' !!}</td>
                <td class="center">{!! ($row['result'] ?? null) === 'rejected' ? $mark : '' !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

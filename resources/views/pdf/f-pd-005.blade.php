{{--
    F-PD-005 PRODUCTION SHEET (ฉบับที่ 2)

    $data = [
        'page' => 1, 'pages' => 1, 'production_no' => '', 'copy_no' => '',
        'product' => '',        // แผ่นพื้นผลิตภัณฑ์ (cm.)
        'wire' => '',           // ลวดอัดแรง (mm./inch)
        'load' => '',           // นน.บรรทุก (กก./ม2)
        'remark' => '',
        'project_name' => '',
        'prepared_date' => null, 'checked_date' => null, 'reviewed_date' => null,
        'approved_date' => null, 'manager_date' => null,
    ];
    // ตารางซ้าย-ขวา เรียงต่อกัน (ซ้ายเต็มแล้วไปขวา) ช่องรายละเอียดแบ่ง 3 ช่อง
    $items = [
        ['code' => '', 'detail_a' => '', 'detail' => '', 'detail_b' => '', 'qty' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-005 ฉบับที่ : 2')
@section('title', 'F-PD-005 Production Sheet')
@section('font-size', '15')

@php
    $data = $data ?? [];
    $items = collect($items ?? [])->values();
    $perSide = $perSide ?? 24;
    $perPage = $perSide * 2;
    $chunks = $items->count() ? $items->chunk($perPage) : collect([collect()]);
    $signers = [
        ['ผู้จัดทำ', 'หัวหน้าส่วน', 'ปฏิบัติการสนาม', 'prepared_date'],
        ['ผู้ตรวจสอบ', 'หัวหน้าแผนก', 'ปฏิบัติการสนาม', 'checked_date'],
        ['ผู้ทวนสอบ', 'จนท. แผนก', 'ปฏิบัติการสนาม', 'reviewed_date'],
        ['ผู้อนุมัติ', 'หัวหน้าแผนก', 'ปฏิบัติการสนาม', 'approved_date'],
        ['', 'ผู้จัดการ', 'ฝ่ายโรงงาน', 'manager_date'],
    ];
@endphp

@section('styles')
    .items td { height: 24px; font-size: 13pt; padding-top: 0; padding-bottom: 0; }
    .items .sub { border-left: 0.4px solid #999; }
@endsection

@section('content')
@foreach ($chunks as $pageIndex => $chunk)
    @php
        $chunk = $chunk->values()->pad($perPage, null);
        $left = $chunk->slice(0, $perSide)->values();
        $right = $chunk->slice($perSide)->values();
    @endphp

    <table class="box-thick">
        <tr>
            <td style="width:30%;"></td>
            <td class="title" style="width:40%; padding-top:4px;">PRODUCTION SHEET</td>
            <td class="right" style="width:30%;">หน้าที่ {{ $data['page'] ?? ($pageIndex + 1) }}/{{ $data['pages'] ?? $chunks->count() }}</td>
        </tr>
        <tr>
            <td class="small">บริษัท วงศ์ชัย จำกัด</td>
            <td colspan="2">
                <table class="ln"><tr>
                    <td style="width:30%;"></td>
                    <td class="l">Production No</td><td class="vc">{{ $data['production_no'] ?? '' }}</td>
                    <td class="l">&nbsp;ฉบับที่</td><td class="vc" style="width:15%;">{{ $data['copy_no'] ?? '' }}</td>
                </tr></table>
            </td>
        </tr>
    </table>

    <table style="border:1.5px solid #000; border-top:none;">
        <tr>
            <td style="width:39%; border-right:0.7px solid #000; vertical-align:top; padding:3px 4px;">
                <table class="ln"><tr><td class="l" style="width:24mm;">แผ่นพื้นผลิตภัณฑ์</td><td class="vc">{{ $data['product'] ?? '' }}</td><td class="l">cm.</td></tr></table>
                <table class="ln"><tr><td class="l" style="width:24mm;">ลวดอัดแรง</td><td class="vc">{{ $data['wire'] ?? '' }}</td><td class="l">mm./ inch</td></tr></table>
                <table class="ln"><tr><td class="l" style="width:24mm;">นน.บรรทุก</td><td class="vc">{{ $data['load'] ?? '' }}</td><td class="l">กก./ม<sup>2</sup></td></tr></table>
                <table class="ln"><tr><td class="l" style="width:24mm;">หมายเหตุ</td><td class="v">{{ $data['remark'] ?? '' }}</td></tr></table>
                <div class="line">&nbsp;</div>
                <div class="line">&nbsp;</div>
            </td>
            <td style="width:61%; vertical-align:top; padding:0;">
                <table class="ln" style="padding:3px 4px;"><tr><td class="l">ชื่อโครงการ</td><td class="v">{{ $data['project_name'] ?? '' }}</td></tr></table>
                <table class="grid" style="border-top:0.7px solid #000;">
                    <tr>
                        @foreach ($signers as $i => $s)
                            <td class="center" style="width:20%; border-top:none; border-bottom:none; {{ $i === 0 ? 'border-left:none;' : '' }} {{ $i === 4 ? 'border-right:none;' : '' }}">
                                <div class="large">{{ $s[0] ?: ' ' }}&nbsp;</div>
                                <div class="xsmall">{{ $s[1] }}</div>
                                <div class="xsmall">{{ $s[2] }}</div>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($signers as $i => $s)
                            <td class="center xsmall" style="height:44px; vertical-align:bottom; border-bottom:none; {{ $i === 0 ? 'border-left:none;' : '' }} {{ $i === 4 ? 'border-right:none;' : '' }}">
                                @if (! empty($data[$s[3]]))
                                    {{ \Illuminate\Support\Carbon::parse($data[$s[3]])->format('d/m/Y') }}
                                @else
                                    ......./......./.......
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="margin-top:6px;">
        <tr>
            @foreach ([$left, $right] as $side => $list)
                <td style="width:49%; padding:0; vertical-align:top;">
                    <table class="grid items" style="border:1.5px solid #000;">
                        <tr>
                            <th style="width:20%;">รหัส</th>
                            <th colspan="3">รายละเอียด</th>
                            <th style="width:17%;">จำนวน</th>
                        </tr>
                        @foreach ($list as $row)
                            <tr>
                                <td class="center">{{ $row['code'] ?? '' }}</td>
                                <td class="center" style="width:11%;">{{ $row['detail_a'] ?? '' }}</td>
                                <td class="sub">{{ $row['detail'] ?? '' }}</td>
                                <td class="center sub" style="width:11%;">{{ $row['detail_b'] ?? '' }}</td>
                                <td class="center">{{ $row['qty'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                @if ($side === 0)
                    <td style="width:2%;"></td>
                @endif
            @endforeach
        </tr>
    </table>

    <div style="margin-top:4px;">หมายเหตุ - หากไม่ระบุหน่วยให้ถือว่าหน่วยเป็นเมตร</div>

    @if (! $loop->last)
        <div class="page-break"></div>
    @endif
@endforeach
@endsection

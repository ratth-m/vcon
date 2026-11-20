{{--
    F-PD-020 ใบรายงานการติดตั้ง (ฉบับที่ 2) — 2 หน้า

    $data = [
        'date' => null,
        'unit' => 'sampran' | 'lamlukka' | 'banbueng',
        'weather' => 'clear' | 'other', 'weather_other' => '',
        'start_time' => '', 'end_time' => '', 'foreman' => '', 'team_leader' => '',
        'project_name' => '', 'location' => '', 'install_round' => '', 'floor' => '', 'zone' => '', 'area' => '',
        // เครื่องมือ
        'crane' => bool, 'crane_ton' => '', 'crane_detail' => '', 'tower_crane' => bool, 'lift_other' => '',
        'tag_truck' => bool, 'tag_wheels' => '', 'tag_no' => '', 'hoist' => bool, 'hoist_m' => '', 'crocodile' => bool, 'special_other' => '',
        'tools_return' => '', 'tools_condition' => 'good' | 'fix', 'tools_fix_detail' => '',
        'work_detail' => "หลายบรรทัด",
        'product_total' => '', 'product_installed' => '', 'product_stock' => '', 'stock_location' => '',
        'eval_site' => "หลายบรรทัด", 'eval_team' => "หลายบรรทัด",
        // ผลการตรวจสอบ: true = ok, string = รายละเอียดที่ไม่ ok
        'check_alignment' => true|'...', 'check_type' => true|'...', 'check_quality' => true|'...',
        'reporter' => '', 'customer_rep' => '',
        // หน้า 2
        'transport_quality' => 'fair' | 'improve', 'transport_note' => '',
        'lift_quality' => 'fair' | 'improve', 'lift_note' => '',
        'lift_arrive' => '', 'lift_finish' => '',
        'product_quality' => 'good' | 'fair' | 'improve', 'product_note' => '',
        'p_total' => '', 'p_good' => '', 'p_dmg_transport' => '', 'p_dmg_install' => '', 'damage_handling' => '',
        'damage_detail' => "หลายบรรทัด",
        'leave_factory' => '', 'return_factory' => '',
        'acknowledged_by' => '', 'acknowledged_date' => null,
    ];
    $vehicles = [  // สูงสุด 8 คัน
        ['no' => '', 'plate' => '', 'driver' => '', 'arrive' => '', 'load' => '', 'damaged' => '', 'assist' => 'ok' | 'improve'],
    ];
    $team = ['ชื่อ 1', 'ชื่อ 2', ...];  // สูงสุด 8 คน
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-020 ฉบับที่ : 2')
@section('title', 'F-PD-020 ใบรายงานการติดตั้ง')
@section('font-size', '13.5')
@section('line-height', '6.2mm')

@php
    $data = $data ?? [];
    $vehicles = collect($vehicles ?? [])->take(8)->pad(8, null);
    $team = collect($team ?? [])->take(8)->pad(8, '');
    $unit = $data['unit'] ?? null;
    $ln = fn ($text, $min) => collect(preg_split('/\r\n|\r|\n/', (string) ($text ?? '')))->filter(fn ($l) => $l !== '')->values()->pad($min, '');
    $cb = fn ($checked) => view('pdf.partials.cb', ['checked' => $checked])->render();
    $checks = [
        'check_alignment' => 'แนวแผ่น ระยะนั่ง',
        'check_type' => 'ชนิดของแผ่น',
        'check_quality' => 'คุณภาพแผ่น',
    ];
@endphp

@section('styles')
    .sec { border: 1px solid #000; border-top: none; padding: 2px 6px 4px 6px; }
    .head { font-size: 15pt; }
@endsection

@section('content')
{{-- ===================== หน้า 1 ===================== --}}
<div style="border:1px solid #000; padding:6px 6px 2px 6px;">
    <div class="title">ใบรายงานการติดตั้ง</div>
    <table><tr>
        <td class="bold" style="padding:0;">บริษัท วงศ์ชัย จำกัด</td>
        <td class="right bold" style="padding:0;">วันที่ : @include('pdf.partials.date', ['value' => $data['date'] ?? null])</td>
    </tr></table>
</div>
<table style="border:1px solid #000; border-top:none;">
    <tr>
        <td style="width:50%; border-right:1px solid #000; vertical-align:top; padding:2px 6px;">
            <table class="ln"><tr>
                <td class="l">หน่วยงานติดตั้ง</td>
                <td class="l">{!! $cb($unit === 'sampran') !!} สามพราน</td>
                <td class="l">{!! $cb($unit === 'lamlukka') !!} ลำลูกกา</td>
                <td class="l">{!! $cb($unit === 'banbueng') !!} บ้านบึง</td><td></td>
            </tr></table>
            <table class="ln"><tr>
                <td class="l">อากาศ</td>
                <td class="l">{!! $cb(($data['weather'] ?? null) === 'clear') !!} แจ่มใส</td>
                <td class="l">{!! $cb(($data['weather'] ?? null) === 'other') !!}</td>
                <td class="v">{{ $data['weather_other'] ?? '' }}</td>
            </tr></table>
            <table class="ln"><tr>
                <td class="l">เริ่มงานเวลา</td><td class="vc">{{ $data['start_time'] ?? '' }}</td><td class="l">น. &nbsp;&nbsp;</td>
                <td class="l">เลิกงานเวลา</td><td class="vc">{{ $data['end_time'] ?? '' }}</td><td class="l">น.</td>
            </tr></table>
            <table class="ln"><tr>
                <td class="l">โฟร์แมน</td><td class="vc">{{ $data['foreman'] ?? '' }}</td>
                <td class="l">&nbsp; หัวหน้าทีม</td><td class="vc">{{ $data['team_leader'] ?? '' }}</td>
            </tr></table>
        </td>
        <td style="width:50%; vertical-align:top; padding:2px 6px;">
            <table class="ln"><tr><td class="l">โครงการ</td><td class="v">{{ $data['project_name'] ?? '' }}</td></tr></table>
            <table class="ln"><tr><td class="l">ที่ตั้ง</td><td class="v">{{ $data['location'] ?? '' }}</td></tr></table>
            <table class="ln"><tr>
                <td class="l">ติดตั้งครั้งที่</td><td class="vc" style="width:12mm;">{{ $data['install_round'] ?? '' }}</td>
                <td class="l">&nbsp; ตำแหน่งที่ทำการติดตั้ง ชั้นที่</td><td class="vc">{{ $data['floor'] ?? '' }}</td>
            </tr></table>
            <table class="ln"><tr>
                <td class="l">โซน</td><td class="vc" style="width:16mm;">{{ $data['zone'] ?? '' }}</td>
                <td class="l">&nbsp;&nbsp;&nbsp; พื้นที่</td><td class="vc">{{ $data['area'] ?? '' }}</td><td class="l">ม<sup>2</sup></td>
            </tr></table>
        </td>
    </tr>
</table>

<div class="sec">
    <div class="head">เครื่องมือที่ใช้ทำงาน</div>
    <table class="ln"><tr>
        <td class="l" style="width:24mm;">เครื่องมือยก</td>
        <td class="l">{!! $cb(! empty($data['crane'])) !!} รถเครน</td><td class="vc" style="width:10mm;">{{ $data['crane_ton'] ?? '' }}</td>
        <td class="l">ตัน (</td><td class="vc" style="width:24mm;">{{ $data['crane_detail'] ?? '' }}</td><td class="l">)</td>
        <td class="l">{!! $cb(! empty($data['tower_crane'])) !!} ทาวเวอร์เครน</td>
        <td class="l">{!! $cb(! empty($data['lift_other'])) !!}</td><td class="v">{{ $data['lift_other'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l" style="width:24mm;">เครื่องมือพิเศษ</td>
        <td class="l">{!! $cb(! empty($data['tag_truck'])) !!} รถแต็ก</td><td class="vc" style="width:10mm;">{{ $data['tag_wheels'] ?? '' }}</td>
        <td class="l">ล้อ หมายเลข</td><td class="vc" style="width:10mm;">{{ $data['tag_no'] ?? '' }}</td>
        <td class="l">&nbsp; {!! $cb(! empty($data['hoist'])) !!} รอก</td><td class="vc" style="width:8mm;">{{ $data['hoist_m'] ?? '' }}</td><td class="l">เมตร</td>
        <td class="l">{!! $cb(! empty($data['crocodile'])) !!} ไอ้เข้</td>
        <td class="l">{!! $cb(! empty($data['special_other'])) !!}</td><td class="v">{{ $data['special_other'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr><td class="l">การส่งเครื่องมือพิเศษกลับ</td><td class="v">{{ $data['tools_return'] ?? '' }}</td></tr></table>
    <table class="ln"><tr>
        <td class="l">สภาพของเครื่องมือพิเศษ</td>
        <td class="l">{!! $cb(($data['tools_condition'] ?? null) === 'good') !!} ดี &nbsp;&nbsp;</td>
        <td class="l">{!! $cb(($data['tools_condition'] ?? null) === 'fix') !!} ควรแก้ไข ดังนี้</td>
        <td class="v">{{ $data['tools_fix_detail'] ?? '' }}</td>
    </tr></table>
</div>

<div class="sec">
    <div class="head">รายละเอียดการปฏิบัติงาน</div>
    @foreach ($ln($data['work_detail'] ?? '', 13) as $line)
        <div class="line">{{ $line }}&nbsp;</div>
    @endforeach
    <table class="ln"><tr>
        <td class="l">จำนวนผลิตภัณฑ์ทั้งหมด</td><td class="vc" style="width:12mm;">{{ $data['product_total'] ?? '' }}</td>
        <td class="l">ชิ้น &nbsp; ติดตั้ง</td><td class="vc" style="width:12mm;">{{ $data['product_installed'] ?? '' }}</td>
        <td class="l">ชิ้น &nbsp; สต็อค</td><td class="vc" style="width:12mm;">{{ $data['product_stock'] ?? '' }}</td>
        <td class="l">ชิ้น บริเวณที่สต็อค</td><td class="v">{{ $data['stock_location'] ?? '' }}</td>
    </tr></table>

    <div class="head" style="margin-top:2px;">ประเมินการปฏิบัติงาน / ข้อแนะนำ</div>
    @php $es = $ln($data['eval_site'] ?? '', 2); $et = $ln($data['eval_team'] ?? '', 2); @endphp
    <table class="ln"><tr><td class="l">เกี่ยวกับหน้างาน / ลูกค้า</td><td class="v">{{ $es[0] }}</td></tr></table>
    <div class="line">{{ $es->slice(1)->implode(' ') }}&nbsp;</div>
    <table class="ln"><tr><td class="l">เกี่ยวกับทีมงาน</td><td class="v">{{ $et[0] }}</td></tr></table>
    <div class="line">{{ $et->slice(1)->implode(' ') }}&nbsp;</div>

    <div class="head" style="margin-top:2px;">ผลการตรวจสอบการติดตั้ง</div>
    @foreach ($checks as $key => $label)
        @php $v = $data[$key] ?? null; @endphp
        <table class="ln"><tr>
            <td class="l" style="width:30mm;">{{ $label }}</td>
            <td class="l" style="width:20mm;">{!! $cb($v === true) !!} ok</td>
            <td class="l">{!! $cb(is_string($v) && $v !== '') !!}</td>
            <td class="v">{{ is_string($v) ? $v : '' }}</td>
            <td style="width:8mm;"></td>
        </tr></table>
    @endforeach
    <table style="margin-top:4px;">
        <tr>
            <td style="width:48%; padding:0;">
                <table class="ln"><tr><td class="l">ผู้รายงาน</td><td class="vc">{{ $data['reporter'] ?? '' }}</td><td style="width:15mm;"></td></tr></table>
            </td>
            <td style="width:52%; padding:0;">
                <table class="ln"><tr><td class="l">ตัวแทนลูกค้า</td><td class="vc">{{ $data['customer_rep'] ?? '' }}</td><td style="width:15mm;"></td></tr></table>
            </td>
        </tr>
        <tr>
            <td style="padding:2px 0 8px 22mm;">(โฟร์แมน)</td>
            <td style="padding:2px 0 8px 18mm;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
        </tr>
    </table>
</div>

{{-- ===================== หน้า 2 ===================== --}}
<div class="page-break"></div>
<div style="border:1px solid #000; padding:2px 6px 4px 6px;">
    <div class="head">รายงานรถขนส่ง / รถยก</div>
    <table>
        <tr class="small">
            <td style="width:8%;">คันที่</td>
            <td style="width:16%;" class="center">ทะเบียน</td>
            <td style="width:16%;" class="center">คนขับ</td>
            <td style="width:13%;" class="center">เวลาถึง site</td>
            <td style="width:10%;" class="center">ของที่ขน</td>
            <td style="width:10%;" class="center">ของชำรุด</td>
            <td class="center">การช่วยงานติดตั้ง</td>
        </tr>
        @foreach ($vehicles as $v)
            <tr>
                @foreach (['no', 'plate', 'driver', 'arrive', 'load', 'damaged'] as $k)
                    <td style="padding:0 4px 0 0;"><div class="line" style="text-align:center;">{{ $v[$k] ?? '' }}&nbsp;</div></td>
                @endforeach
                <td style="white-space:nowrap; padding-left:14px;">
                    {!! $cb(($v['assist'] ?? null) === 'ok') !!} ok. &nbsp;
                    {!! $cb(($v['assist'] ?? null) === 'improve') !!} ควรปรับปรุง
                </td>
            </tr>
        @endforeach
    </table>
    @foreach (['transport' => 'คุณภาพของงานขนส่ง', 'lift' => 'คุณภาพของงานรถยก'] as $k => $label)
        <table class="ln"><tr>
            <td class="l" style="width:30mm;">{{ $label }}</td>
            <td class="l" style="width:16mm;">{!! $cb(($data[$k.'_quality'] ?? null) === 'fair') !!} พอใช้</td>
            <td class="l">{!! $cb(($data[$k.'_quality'] ?? null) === 'improve') !!} ควรปรับปรุง</td>
            <td class="v">{{ $data[$k.'_note'] ?? '' }}</td>
        </tr></table>
    @endforeach
    <table class="ln"><tr>
        <td class="l" style="padding-left:4mm;">เวลารถยกถึงหน่วยงาน</td><td class="vc">{{ $data['lift_arrive'] ?? '' }}</td><td class="l">น.</td>
        <td style="width:20mm;"></td>
        <td class="l">เวลารถยกเลิกปฏิบัติงาน</td><td class="vc">{{ $data['lift_finish'] ?? '' }}</td><td class="l">น.</td>
    </tr></table>
</div>

<div class="sec">
    <div class="head">รายงานผลิตภัณฑ์</div>
    <table class="ln"><tr>
        <td class="l" style="width:30mm;">คุณภาพของผลิตภัณฑ์</td>
        <td class="l" style="width:16mm;">{!! $cb(($data['product_quality'] ?? null) === 'good') !!} ดี</td>
        <td class="l" style="width:20mm;">{!! $cb(($data['product_quality'] ?? null) === 'fair') !!} พอใช้</td>
        <td class="l">{!! $cb(($data['product_quality'] ?? null) === 'improve') !!} ควรปรับปรุง</td>
        <td></td>
    </tr></table>
    <div class="line" style="width:50%;">{{ $data['product_note'] ?? '' }}&nbsp;</div>
    <table class="ln"><tr>
        <td class="l">จำนวนผลิตภัณฑ์ทั้งหมด</td><td class="vc">{{ $data['p_total'] ?? '' }}</td>
        <td class="l">ชิ้น &nbsp; ผลิตภัณฑ์ดี</td><td class="vc">{{ $data['p_good'] ?? '' }}</td>
        <td class="l">ชิ้น &nbsp; ผลิตภัณฑ์ชำรุดขนส่ง</td><td class="vc">{{ $data['p_dmg_transport'] ?? '' }}</td>
        <td class="l">ชิ้น &nbsp; ผลิตภัณฑ์ชำรุดติดตั้ง</td><td class="vc">{{ $data['p_dmg_install'] ?? '' }}</td><td class="l">ชิ้น</td>
    </tr></table>
    <table class="ln"><tr><td class="l">การจัดการกับผลิตภัณฑ์ชำรุด</td><td class="v">{{ $data['damage_handling'] ?? '' }}</td></tr></table>
    <div class="head" style="margin-top:2px;">ลักษณะการชำรุด</div>
    <div style="height:62mm; padding:2px 4px; white-space:pre-line;">{{ $data['damage_detail'] ?? '' }}</div>
</div>

<div class="sec">
    <div class="head">รายชื่อทีมงานติดตั้ง</div>
    <table>
        <tr>
            <td class="center" style="width:25%;">ชื่อ</td><td class="center" style="width:25%;">ลายเซ็น</td>
            <td class="center" style="width:25%;">ชื่อ</td><td class="center" style="width:25%;">ลายเซ็น</td>
        </tr>
        @for ($n = 0; $n < 4; $n++)
            <tr>
                <td style="padding:0 8px 0 0;"><table class="ln"><tr><td class="l">{{ $n + 1 }}.</td><td class="v">{{ $team[$n] }}</td></tr></table></td>
                <td style="padding:0 8px 0 0;"><table class="ln"><tr><td class="v">&nbsp;</td></tr></table></td>
                <td style="padding:0 8px 0 0;"><table class="ln"><tr><td class="l">{{ $n + 5 }}.</td><td class="v">{{ $team[$n + 4] }}</td></tr></table></td>
                <td style="padding:0;"><table class="ln"><tr><td class="v">&nbsp;</td></tr></table></td>
            </tr>
        @endfor
    </table>
    <table class="ln" style="margin-top:2px;"><tr>
        <td class="l" style="padding-left:4mm;">เวลาออกจากโรงงาน</td><td class="vc">{{ $data['leave_factory'] ?? '' }}</td><td class="l">น.</td>
        <td style="width:24mm;"></td>
        <td class="l">เวลากลับถึงโรงงาน</td><td class="vc">{{ $data['return_factory'] ?? '' }}</td><td class="l">น.</td>
        <td style="width:24mm;"></td>
    </tr></table>
    <table style="margin-top:14px;">
        <tr>
            <td style="width:42%; padding:0;"><table class="ln"><tr><td class="l">ผู้รายงาน</td><td class="vc">{{ $data['reporter'] ?? '' }}</td></tr></table></td>
            <td style="width:58%; padding:0 0 0 10mm;"><table class="ln"><tr><td class="l">ผู้รับทราบ</td><td class="vc">{{ $data['acknowledged_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['acknowledged_date'] ?? null])</td></tr></table></td>
        </tr>
        <tr>
            <td style="padding:2px 0 4px 20mm;">(โฟร์แมน)</td>
            <td style="padding:2px 0 4px 30mm;">(หัวหน้าส่วนปฏิบัติการสนาม)</td>
        </tr>
    </table>
</div>
@endsection

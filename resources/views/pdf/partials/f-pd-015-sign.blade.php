{{-- กล่องลงชื่อของ F-PD-015 (ใช้ทั้งส่วนที่ 1 และ 2) --}}
@php $blank = '( '.str_repeat('&nbsp;', 44).' )'; @endphp
<table style="border:1px solid #000;">
    <tr>
        <td style="width:62%; border-right:1px solid #000; vertical-align:top; padding:2px 6px;">
            <div>บริษัท วงศ์ชัย จำกัด</div>
            <table class="ln"><tr><td class="l" style="width:18mm;">ผู้จัดทำ</td><td class="vc">{{ $data['prepared_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['prepared_date'] ?? null])</td></tr></table>
            <div style="padding:3px 0 0 18mm;">{!! $blank !!}</div>
            <table class="ln" style="margin-top:6px;"><tr><td class="l" style="width:18mm;">ผู้ตรวจสอบ</td><td class="vc">{{ $data['checked_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['checked_date'] ?? null])</td></tr></table>
            <div style="padding:3px 0 0 18mm;">{!! $blank !!}</div>
        </td>
        <td style="width:38%; vertical-align:top; padding:2px 6px;">
            <div>ตัวแทนลูกค้า</div>
            <table class="ln"><tr><td class="l" style="width:14mm;">ผู้อนุมัติ</td><td class="vc">{{ $data['customer_approver'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['customer_approver_date'] ?? null])</td></tr></table>
            <div style="padding:3px 0 0 8mm;">( {!! str_repeat('&nbsp;', 36) !!} )</div>
            <table class="ln" style="margin-top:6px;"><tr><td class="l" style="width:14mm;"></td><td class="vc">{{ $data['customer_signer2'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['customer_signer2_date'] ?? null])</td></tr></table>
            <div style="padding:3px 0 0 8mm;">( {!! str_repeat('&nbsp;', 36) !!} )</div>
        </td>
    </tr>
</table>
<div class="small" style="padding-left:6px;">กรุณาลงชื่อและสกุลตัวบรรจงใน ( )</div>

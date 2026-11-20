{{-- checkbox: @include('pdf.partials.cb', ['checked' => bool]) --}}
<span class="cb">{!! !empty($checked) ? '&#10003;' : '&nbsp;' !!}</span>

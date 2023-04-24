@if (Session::has('error'))
<div class="alert alert-danger validation">
    <button type="button" class="close alert-close"><span>×</span></button>
    <p class="text-left">{{ Session::get('error') }}</p>
</div>
@php
    Session::forget('error');
@endphp
@endif
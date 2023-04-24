@if (Session::has('success'))
<div class="alert alert-success validation">
    <button type="button" class="close alert-close"><span>×</span></button>
    <p class="text-left">{{ Session::get('success') }}</p>
</div>
@php
    Session::forget('success');
@endphp
@endif
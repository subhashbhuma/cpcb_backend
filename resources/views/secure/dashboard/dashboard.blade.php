@extends('layouts.app_layout')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    @include('secure.dashboard.roles.super_admin')
@endsection

@section('pages-scripts')
    <script @cspNonce src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
@endsection
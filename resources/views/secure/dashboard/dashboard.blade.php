@extends('layouts.app_layout')

@section('content')
    @php
        $user = auth()->user();
    @endphp

    @if(Auth::user()->roles->first()->name == 'EMPLOYEE')
    @include('secure.dashboard.roles.employee')
    @else
    @include('secure.dashboard.roles.super_admin')
    @endif

   
@endsection

@section('pages-scripts')
    <script @cspNonce src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>
@endsection
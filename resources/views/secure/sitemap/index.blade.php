@extends('layouts.app_layout')

@section('content')
    <x-page-header title="{{ $pageTitle }}">
        <x-slot name="button">
            <a href="{{ route('sitemap.export') }}" class="btn btn-success" id="exportSitemapBtn">
                <i class="ti ti-download"></i> Export Sitemap (Excel)
            </a>
        </x-slot>
    </x-page-header>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-sitemap text-primary"></i>
                        Sitemap Preview
                        <span class="badge bg-primary ms-2">{{ $menus->count() }} Menus</span>
                    </h5>
                    <p class="text-muted mb-0 mt-1">
                        Menus are shown in parent → child order, header location first.
                    </p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="sitemap-table">
                            <thead>
                                <tr>
                                    <th width="60">Sl No</th>
                                    <th>Title</th>
                                    <th>URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $index => $menu)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td style="padding-left: {{ 20 + ($menu->depth * 25) }}px;">
                                            @if($menu->depth > 0)
                                                <span class="text-muted">{{ str_repeat('— ', $menu->depth) }}</span>
                                            @endif
                                            {{ $menu->title }}
                                        </td>
                                        <td>
                                            @if($menu->full_url)
                                                <a href="{{ $menu->full_url }}" target="_blank" rel="noopener noreferrer">
                                                    {{ $menu->full_url }}
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="ti ti-info-circle" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2">No menus found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

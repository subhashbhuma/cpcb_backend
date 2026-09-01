@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('home-about.edit', $homeAbout->id);
    @endphp
    @can('edit homepage about')
        @php
            $button = '<a href="' . $addRoute . '" class="btn btn-primary"><i class="fa fa-edit"></i> Edit Details</a>';
        @endphp
    @endcan
    <x-page-header title="{{ $pageTitle }}" button="{!! (isset($button)) ? $button : '' !!}" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Image File:</strong></label>
                            <div>
                                @if ($homeAbout->image)
                                    <img src="{{ $homeAbout->image_path }}" alt="{{ $homeAbout->title ?? '—' }}"
                                        class="img-fluid border" style="max-height: 150px;">
                                @else
                                    <p class="text-muted mb-0">No image uploaded.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (English):</strong></label>
                            <p class="mb-0">{{ $homeAbout->title ?? '—' }}</p>
                        </div>

                        <div class="col-md-6 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Title (Hindi):</strong></label>
                            <p class="mb-0">{{ $homeAbout->title_hi ?? '—' }}</p>
                        </div>

                        <div class="col-md-12 card py-2 bg-light mb-3">
                            <label class="form-label"><strong>Button Link:</strong></label>
                            <p class="mb-0">
                                {{ $homeAbout->button_link }}
                            </p>
                        </div>

                        <div class="col-md-12 card py-2 bg-light mb-3" style="max-height: 400px; overflow-y: auto;">
                            <label class="form-label">Description:</label>
                            <div class="border p-3">
                                @if ($homeAbout->description)
                                    {!! $homeAbout->description !!}
                                @else
                                    <span class="text-danger"> No description available</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 card py-2 bg-light mb-3" style="max-height: 400px; overflow-y: auto;">
                            <label class="form-label">Description (हिंदी):</label>
                            <div class="border p-3">
                                @if ($homeAbout->description_hi)
                                    {!! $homeAbout->description_hi !!}
                                @else
                                    <span class="text-danger"> No description available</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
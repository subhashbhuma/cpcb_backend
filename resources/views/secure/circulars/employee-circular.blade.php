@extends('layouts.app_layout')

@section('style')
    <style>
        .filter-btn-group {
            margin-left: 15px;
        }

        .filter-btn-group .btn {
            margin-right: 8px;
            border-radius: 12px;
            padding: 6px 16px;
            font-weight: 600;
            transition: all .3s ease;
            position: relative;
            overflow: hidden;
        }

        .filter-btn-group .btn i {
            font-size: 16px;
            transition: all .3s ease;
        }

        .filter-btn-group .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        .filter-btn-group .btn:hover i {
            transform: scale(1.15) rotate(-8deg);
        }

        .filter-btn-group .btn.active {
            background: linear-gradient(135deg, #0d6efd, #0056d2);
            color: #fff !important;
            border-color: #0d6efd;
            box-shadow: 0 8px 18px rgba(13, 110, 253, .25);
        }

        .animated-btn {
            animation: fadeInUp .4s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')

    <!-- Page Header -->
    @php
        $addRoute = route('circulars.create');
    @endphp

    @can('add circular')
        @php
            $button =
                '<a href="' .
                $addRoute .
                '" class="btn btn-primary">
                                                                                                                                                                                                                                            <i class="fa fa-plus"></i> Add New
                                                                                                                                                                                                                                        </a>';
        @endphp
    @endcan

    <x-page-header title="{{ $pageTitle }}" button="{!! isset($button) ? $button : '' !!}" />

    <!-- Content -->
    <div class="row">
        <div class="col-12">

            <div class="card">

                <div class="card-body">

                    <div class="dt-responsive">

                        <!-- DataTable -->
                        <table id="circulars-datatable" class="table table-striped table-bordered">

                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Division</th>
                                    <th>Title</th>
                                    <th>File (English)</th>
                                    <th>File (हिंदी)</th>
                                    <th>Date of Issue</th>
                                </tr>
                            </thead>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            // Default Filter
            let archiveFilter = 'latest';
            // DataTable Initialize
            var table = $('#circulars-datatable').DataTable({

                processing: true,
                serverSide: true,
                ordering: false,


                ajax: {
                    url: "{{ route('circulars.fetch-for-datatable') }}",
                    type: "POST",

                    data: function (d) {

                        d._token = $('meta[name="csrf-token"]').attr('content');

                        // Archive Filter
                        d.archive_status = archiveFilter;

                        // Other Filter
                        d.category = {{ $categoryId }};
                    }
                },

                columns: [{
                    data: null,
                    name: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                    width: '7%',
                },

                {
                    data: 'division.title',
                    name: 'division.title',
                    width: '10%',
                },

                {
                    data: 'title',
                    name: 'title',
                    width: '53%',
                },

                {
                    data: 'file',
                    name: 'file_name',
                    width: '10%',
                },

                {
                    data: 'file_hi',
                    name: 'file_name_hi',
                    width: '10%',
                },

                {
                    data: 'published_date',
                    name: 'published_date',
                    width: '10%',
                }
                ],

                initComplete: function () {
                    // Filter Buttons
                    let filterButtons = `
                                    <div class="btn-group mx-3 filter-btn-group d-flex flex-wrap"
                                        role="group">

                                        <button type="button"
                                            class="btn btn-primary animated-btn archive-filter active"
                                            data-value="latest">

                                            <i class="ti ti-trending-up mr-1"></i>
                                            Latest
                                        </button>

                                        <button type="button"
                                            class="btn btn-outline-primary animated-btn archive-filter"
                                            data-value="archive">

                                            <i class="ti ti-archive mr-1"></i>
                                            Archive
                                        </button>

                                    </div>
                                `;

                    // Append beside Show Entries
                    $('#circulars-datatable_length')
                        .addClass('d-flex align-items-center flex-wrap')
                        .append(filterButtons);
                }
            });

            /**
             * Set Archive Filter
             */
            function setArchiveFilter(value) {

                archiveFilter = value;

                // Reset Buttons
                $('.archive-filter')
                    .removeClass('active btn-primary')
                    .addClass('btn-outline-primary');

                // Active Button
                $(`.archive-filter[data-value="${value}"]`)
                    .addClass('active btn-primary')
                    .removeClass('btn-outline-primary');

                // Reload Table
                table.draw();
            }

            /**
             * Filter Button Click
             */
            $(document).on('click', '.archive-filter', function () {

                let value = $(this).data('value');

                setArchiveFilter(value);
            });

            /**
             * Other Filters
             */
            $('#status_filter').on('change', function () {

                table.draw();
            });


        });
    </script>
@endsection
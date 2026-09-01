@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    <x-page-header :title="$page->title" :backButton="false" />
    <!-- [ Page Header ] end -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($page->content)
                        {!! $page->content !!}
                    @else
                        <p class="text-muted">No content available for this page.</p>
                    @endif
                    
                    @if(count($page->files ?? []) > 0)
                        <hr>
                        <h5 class="mt-4">Attachments / Files</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="8%">S.No.</th>
                                        <th width="40%">Title</th>
                                        <th width="32%">File</th>
                                        <th width="20%">Upload Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($page->files as $key => $file)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $file->title }}
                                                @if($file->title_hi)
                                                    <br>({{ $file->title_hi }})
                                                @endif
                                            </td>
                                            <td>
                                                @if ($file->file_name)
                                                    <a href="{{ generate_file_view_path_for_backend($file->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye"></i> View File (English)
                                                    </a>
                                                @endif
                                                @if ($file->file_name_hi)
                                                    <a href="{{ generate_file_view_path_for_backend($file->file_path_hi) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                                        <i class="fa fa-eye"></i> View File (हिंदी)
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $file->upload_date ? date('d-m-Y', strtotime($file->upload_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

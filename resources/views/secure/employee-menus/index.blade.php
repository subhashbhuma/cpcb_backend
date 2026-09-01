@extends('layouts.app_layout')

@section('content')
    <!-- [ Page Header ] start -->
    @php
        $addRoute = route('employee-menus.create');
    @endphp
    <x-page-header title="{{ $pageTitle }}"
        button='<a href="{{ $addRoute }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>' />

    <!-- [ Page Header ] end -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    @if(count($menus) > 0)
                        {{-- {{dd($menus)}} --}}
                        <div class="dd" id="nestable-menu">
                            <ol class="dd-list menu-list">
                                @foreach ($menus as $menu)
                                    <li class="dd-item menu-list-item" data-id="{{ $menu->id }}">
                                        <div class="menu-list-item-div">
                                            <div class="dd-handle">
                                                <span class="menu-list-item-link">
                                                    {{ $menu->title }} ({{ $menu->title_hi }})
                                                </span>
                                            </div>
                                            <div class="menu-actions">
                                                <a href="{{ route('employee-menus.edit', $menu->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('employee-menus.destroy', $menu->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @if ($menu->children->isNotEmpty())
                                            <ol class="dd-list">
                                                @foreach ($menu->children as $child)
                                                    @include('partials.secure.employee-menus.menu-item', ['menu' => $child])
                                                @endforeach
                                            </ol>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        <div class="text-center mt-3">
                            <button id="save-order" class="btn btn-success">
                                Save Order
                            </button>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <p class="mb-0">
                                No employee menus are available.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {

            $('#nestable-menu').nestable({
                maxDepth: 10 // Adjust the maximum depth as needed
            });

            $('#save-order').on('click', function () {
                var order = $('#nestable-menu').nestable('serialize');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('employee-menus.updateOrder') }}",
                    data: {
                        order: order,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Display SweetAlert2 success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Employee Menu order has been updated.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Reload the page after user clicks 'OK'
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                    error: function (xhr) {
                        // Display SweetAlert2 error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while updating the employee menu order.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

        });
    </script>
@endsection

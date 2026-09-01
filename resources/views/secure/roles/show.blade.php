@extends('layouts.app_layout')

@section('content')
<x-page-header title="{{ $pageTitle }}" :backButton="true" />

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4 class="mb-1">Role Name</h4>
                        <p class="text-muted mb-0">{{ $role->name }}</p>
                    </div>
                </div>

                <hr>

                <h4 class="mb-3">Permissions</h4>
                <div class="row">
                    @forelse($permissionGroups as $group => $permissions)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0">{{ $group }}</h6>
                            </div>
                            <div class="card-body py-2">
                                <ul class="list-unstyled mb-0">
                                    @foreach($permissions as $permission)
                                    <li class="mb-1">
                                        <i class="fa fa-check text-success me-2"></i> {{ $permission->name }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-warning">
                            No permissions assigned to this role.
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

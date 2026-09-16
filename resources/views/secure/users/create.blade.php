@extends('layouts.app_layout')

@section('content')
    <style @cspNonce>
        .menu-row:hover { background-color: rgba(0, 123, 255, 0.05) !important; }
        .perm-cell { transition: all 0.2s; border-left: 1px solid #f1f1f1; }
        .perm-cell:hover { background-color: rgba(0, 0, 0, 0.02); }
        .perm-check-wrapper { cursor: pointer; display: block; width: 100%; height: 100%; padding: 8px 0; }
        .perm-checkbox:checked+.form-check-label { font-weight: bold; }
        .bg-view { background-color: rgba(40, 167, 69, 0.03); }
        .bg-add { background-color: rgba(0, 123, 255, 0.03); }
        .bg-edit { background-color: rgba(255, 193, 7, 0.03); }
        .bg-delete { background-color: rgba(220, 53, 69, 0.03); }
        .bg-publish { background-color: rgba(111, 66, 193, 0.03); }
        .bg-approve { background-color: rgba(232, 62, 140, 0.03); }
        .bg-view:has(.perm-checkbox:checked) { background-color: rgba(40, 167, 69, 0.12) !important; }
        .bg-add:has(.perm-checkbox:checked) { background-color: rgba(0, 123, 255, 0.1) !important; }
        .bg-edit:has(.perm-checkbox:checked) { background-color: rgba(255, 193, 7, 0.12) !important; }
        .bg-delete:has(.perm-checkbox:checked) { background-color: rgba(220, 53, 69, 0.1) !important; }
        .bg-publish:has(.perm-checkbox:checked) { background-color: rgba(111, 66, 193, 0.1) !important; }
        .bg-approve:has(.perm-checkbox:checked) { background-color: rgba(232, 62, 140, 0.1) !important; }
        .menu-row:has(.perm-checkbox:checked) { background-color: rgba(0, 123, 255, 0.02) !important; }
        .row-caption { background-color: #f8f9fa !important; border-bottom: 2px solid #dee2e6; }
        .row-parent { background-color: #fcfcfc !important; border-bottom: 1px solid #eee; }
    </style>

    <x-page-header title="{{ $pageTitle }}" :backButton="true" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="userForm" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Full Name"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email"
                                    required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="mobile_number" class="form-control" id="mobile_number"
                                    placeholder="Enter Mobile Number" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label d-block">Assign Roles <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-3 mt-2">
                                    <select name="roles" id="roles" class="form-control" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="roles-error" class="text-danger mt-1"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="division_id" class="form-label">Division</label>
                                <select name="division_id" id="division_id" class="form-control select2">
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}">{{ $division->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Permission Matrix Sections -->
                        <div class="mt-4">
                            <h5 class="mb-3"><i class="fas fa-shield-alt text-primary me-2"></i> User Menu Permissions</h5>

                            <div class="row">
                                <!-- Sidebar Menus (Modules) -->
                                <div class="col-md-12">
                                    <div class="card border shadow-none mb-3" style="border-left: 4px solid #0d6efd !important;">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                            <h6 class="mb-0"><i class="ti ti-layout-sidebar-left-collapse me-1 text-primary"></i> Sidebar Menus (Modules)</h6>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" id="selectAllSidebar">
                                                    <label class="form-check-label fw-bold" for="selectAllSidebar">Select
                                                        All</label>
                                                </div>
                                                <input type="text" id="searchSidebar" class="form-control form-control-sm"
                                                    placeholder="Search sidebar..." style="width: 150px;">
                                            </div>
                                        </div>
                                        <div class="card-body p-0 position-relative"
                                            style="max-height: 600px; overflow-y: auto;">
                                            <div class="alert alert-info alert-dismissible fade show mb-0 py-2 px-3 rounded-0 border-0" role="alert" style="font-size: 0.8rem;">
                                                <i class="ti ti-info-circle me-1"></i> <strong>Admin Sidebar:</strong> These permissions control which menu items appear in the <strong>admin panel sidebar</strong> after login. This is different from the Page Menus section below.
                                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" style="font-size: 0.6rem;"></button>
                                            </div>
                                            <!-- Fixed Master Header -->
                                            <div class="sticky-top bg-light border-bottom shadow-sm"
                                                style="z-index: 1030; top: 0;">
                                                <table class="table table-sm mb-0" style="table-layout: fixed;">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                style="width: 40%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-bottom: 0;">
                                                                Menu</th>
                                                            <th class="text-center bg-light"
                                                                style="width: 6%; border-bottom: 0;">All</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #28a745; color: #28a745; font-size: 0.8rem;">
                                                                View</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #007bff; color: #007bff; font-size: 0.8rem;">
                                                                Add</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #ffc107; color: #ffc107; font-size: 0.8rem;">
                                                                Edit</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #dc3545; color: #dc3545; font-size: 0.8rem;">
                                                                Delete</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #6f42c1; color: #6f42c1; font-size: 0.8rem;">
                                                                Publish</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #e83e8c; color: #e83e8c; font-size: 0.8rem;">
                                                                Approve</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                            <div class="accordion" id="sidebarAccordion">
                                                @foreach($sidebarGroups as $index => $group)
                                                    <div class="accordion-item border-0 border-bottom">
                                                        <h2 class="accordion-header" id="sidebarHeading{{ $index }}">
                                                            <button
                                                                class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} bg-light py-2"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#sidebarCollapse{{ $index }}"
                                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                                                <span
                                                                    class="fw-bold text-dark">{{ $group['root']->title }}</span>
                                                            </button>
                                                        </h2>
                                                        <div id="sidebarCollapse{{ $index }}"
                                                            class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                                            data-bs-parent="#sidebarAccordion">
                                                            <div class="accordion-body p-0">
                                                                <table class="table table-sm table-hover mb-0"
                                                                    style="table-layout: fixed;">
                                                                    <tbody class="border-top-0">
                                                                        @foreach($group['menus'] as $menu)
                                                                            @include('secure.users.partials.permission_row', ['rowId' => $menu->id])
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Page Menus (Content) -->
                                <div class="col-md-12">
                                    <div class="card border shadow-none mb-3" style="border-left: 4px solid #fd7e14 !important;">
                                        <div
                                            class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                            <h6 class="mb-0"><i class="ti ti-browser me-1" style="color: #fd7e14;"></i> Page Menus (Frontend Content)</h6>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" id="selectAllPages">
                                                    <label class="form-check-label fw-bold" for="selectAllPages">Select
                                                        All</label>
                                                </div>
                                                <input type="text" id="searchPages" class="form-control form-control-sm"
                                                    placeholder="Search pages..." style="width: 150px;">
                                            </div>
                                        </div>
                                        <div class="card-body p-0 position-relative"
                                            style="max-height: 600px; overflow-y: auto;">
                                            <div class="alert alert-warning alert-dismissible fade show mb-0 py-2 px-3 rounded-0 border-0" role="alert" style="font-size: 0.8rem;">
                                                <i class="ti ti-info-circle me-1"></i> <strong>Frontend Pages:</strong> These permissions control access to <strong>website frontend content</strong> (header menus, inner pages). They do <strong>NOT</strong> affect the admin sidebar.
                                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" style="font-size: 0.6rem;"></button>
                                            </div>
                                            <!-- Fixed Master Header -->
                                            <div class="sticky-top bg-light border-bottom shadow-sm"
                                                style="z-index: 1030; top: 0;">
                                                <table class="table table-sm mb-0" style="table-layout: fixed;">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                style="width: 40%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; border-bottom: 0;">
                                                                Menu</th>
                                                            <th class="text-center bg-light"
                                                                style="width: 6%; border-bottom: 0;">All</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #28a745; color: #28a745; font-size: 0.8rem;">
                                                                View</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #007bff; color: #007bff; font-size: 0.8rem;">
                                                                Add</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #ffc107; color: #ffc107; font-size: 0.8rem;">
                                                                Edit</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #dc3545; color: #dc3545; font-size: 0.8rem;">
                                                                Delete</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #6f42c1; color: #6f42c1; font-size: 0.8rem;">
                                                                Publish</th>
                                                            <th class="text-center"
                                                                style="width: 9%; border-bottom: 2px solid #e83e8c; color: #e83e8c; font-size: 0.8rem;">
                                                                Approve</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                            <div class="accordion" id="pagesAccordion">
                                                @foreach($pageGroups as $index => $group)
                                                    <div class="accordion-item border-0 border-bottom">
                                                        <h2 class="accordion-header" id="pagesHeading{{ $index }}">
                                                            <button
                                                                class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} bg-light py-2"
                                                                type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#pagesCollapse{{ $index }}"
                                                                aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                                                <span
                                                                    class="fw-bold text-dark">{{ $group['root']->title }}</span>
                                                            </button>
                                                        </h2>
                                                        <div id="pagesCollapse{{ $index }}"
                                                            class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}"
                                                            data-bs-parent="#pagesAccordion">
                                                            <div class="accordion-body p-0">
                                                                <table class="table table-sm table-hover mb-0"
                                                                    style="table-layout: fixed;">
                                                                    <tbody class="border-top-0">
                                                                        @foreach($group['menus'] as $menu)
                                                                            @include('secure.users.partials.permission_row', ['rowId' => $menu->id])
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fa fa-plus me-2"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pages-scripts')
    <script @cspNonce>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: '--Select--',
                allowClear: true
            });
            // Live Search Logic
            function filterTable(inputId, accordionId) {
                $('#' + inputId).on('keyup', function () {
                    var value = $(this).val().toLowerCase();
                    $('#' + accordionId + ' .accordion-item').each(function () {
                        var hasMatch = false;
                        $(this).find('.menu-row').each(function () {
                            var text = $(this).text().toLowerCase();
                            var match = text.indexOf(value) > -1;
                            $(this).toggle(match);
                            if (match) hasMatch = true;
                        });
                        $(this).toggle(hasMatch);
                    });
                });
            }
            filterTable('searchSidebar', 'sidebarAccordion');
            filterTable('searchPages', 'pagesAccordion');

            // Section Select All - Sidebar
            $('#selectAllSidebar').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('#sidebarAccordion .perm-checkbox, #sidebarAccordion .select-menu-all').prop('checked', isChecked).trigger('change');
            });

            // Section Select All - Pages
            $('#selectAllPages').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('#pagesAccordion .perm-checkbox, #pagesAccordion .select-menu-all').prop('checked', isChecked).trigger('change');
            });

            // Select all for a single menu row
            $(document).on('change', '.select-menu-all', function () {
                var rowId = $(this).data('row');
                $('.row-' + rowId).prop('checked', $(this).is(':checked')).trigger('change');
            });

            function checkGlobalSelectAll() {
                // Global Select All removed as per request
            }


            // Update row "All" checkbox when individual checkboxes change
            $(document).on('change', '.perm-checkbox', function () {
                var classes = $(this).attr('class').split(' ');
                var rowClass = classes.find(c => c.startsWith('row-') && c !== 'row-all');
                if (rowClass) {
                    var rowId = rowClass.replace('row-', '');
                    var total = $('.row-' + rowId).length;
                    var checked = $('.row-' + rowId + ':checked').length;
                    $('[data-row="' + rowId + '"]').prop('checked', total === checked);
                }
            });

            $("#userForm").validate({
                rules: {
                    name: "required",
                    email: { required: true, email: true },
                    mobile_number: "required",
                    roles: "required"
                },
                errorPlacement: function (error, element) {
                    if (element.attr("name") === "roles") {
                        error.appendTo("#roles-error");
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function (form) {
                    showLoader();
                    $.ajax({
                        url: "{{ route('users.store') }}",
                        method: "POST",
                        data: $(form).serialize(),
                        success: function (response) {
                            hideLoader();
                            if (response.success) {
                                Swal.fire({ title: "Success!", text: response.message, icon: "success" })
                                    .then(() => window.location.href = "{{ route('users.index') }}");
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function (xhr) {
                            hideLoader();
                            if (xhr.status === 422) {
                                let errorMessages = Object.values(xhr.responseJSON.errors).flat().join("<br>");
                                Swal.fire({ title: "Validation Error", html: errorMessages, icon: "error" });
                            } else {
                                Swal.fire({ title: "Error!", text: "Something went wrong.", icon: "error" });
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
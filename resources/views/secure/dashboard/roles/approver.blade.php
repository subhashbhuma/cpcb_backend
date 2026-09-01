<div class="row">
    <div class="col-12 mb-3">
        <h4 class="text-primary">Approver Dashboard</h4>
        <p class="text-muted">Overview of items pending your approval</p>
    </div>
    
    @include('secure.dashboard.roles.partials.content_cards')

    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header bg-light-warning">
                <h5 class="text-warning"><i class="ti ti-alert-circle"></i> Pending Approvals Action</h5>
            </div>
            <div class="card-body">
                <p>Welcome to the Approver dashboard. Please review the "Pending" items from the cards above and process them accordingly.</p>
            </div>
        </div>
    </div>
</div>

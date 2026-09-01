<div class="row">
    <div class="col-12 mb-3">
        <h4 class="text-primary">Content Manager Dashboard</h4>
        <p class="text-muted">Overview of your content entries</p>
    </div>
    
    @include('secure.dashboard.roles.partials.content_cards')

    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h5>Recent Content Highlights</h5>
            </div>
            <div class="card-body">
                <p>Welcome to the Content Manager dashboard. Use the navigation to manage data entries, upload documents, and track their pending approval status.</p>
            </div>
        </div>
    </div>
</div>

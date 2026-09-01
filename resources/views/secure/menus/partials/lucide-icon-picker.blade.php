<style @cspNonce>
    /* Lucide Icon Picker Styles - Official UI match */
    #lucideIconPickerModal .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }


    #lucideIconPickerModal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        background: #fff !important;
        color: #333 !important;
    }
    
    #lucideIconPickerModal .btn-close {
        filter: invert(1);
    }

    .icon-picker-body {
        background-color: #fafafa;
        overflow-x: hidden;
    }

    .lucide-search-input {
        background-color: #f1f5f9 !important;
        border: 1px solid transparent !important;
        border-radius: 12px !important;
        padding-left: 45px !important;
        font-size: 1rem !important;
        box-shadow: none !important;
        height: 48px;
    }
    
    .lucide-search-input:focus {
        border-color: #cbd5e1 !important;
        background-color: #fff !important;
    }

    .lucide-search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        z-index: 4;
    }

    .lucide-grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(52px, 1fr));
        gap: 8px;
        padding-bottom: 20px;
    }

    .icon-btn {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        background: #f6f6f7;
        color: #334155;
        cursor: pointer;
        transition: all 0.1s ease;
        padding: 0;
        position: relative;
    }
    
    /* Native CSS Tooltip to fix overlap issues */
    .icon-btn::after {
        content: attr(data-icon);
        position: absolute;
        bottom: calc(100% + 5px);
        left: 50%;
        transform: translateX(-50%);
        background-color: #1e293b;
        color: #fff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.15s ease-in-out;
        pointer-events: none;
        z-index: 1060;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .icon-btn:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #0f172a;
    }
    
    .icon-btn:hover::after {
        opacity: 1;
        visibility: visible;
    }
    
    .icon-btn:active {
        transform: scale(0.95);
        background: #f1f5f9;
    }
</style>

<div class="modal fade" id="lucideIconPickerModal" tabindex="-1" aria-labelledby="lucideIconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header align-items-center">
                <div>
                    <h5 class="modal-title mb-0 fw-bold" id="lucideIconPickerModalLabel">
                        <img src="https://lucide.dev/logo.light.svg" style="height: 24px; margin-right: 8px; margin-top: -4px;" alt="Lucide" onerror="this.outerHTML='<i class=\'fa fa-icons me-2\'></i>'"> Lucide Icons
                    </h5>
                    <small class="text-muted">Total icons: <span id="lucideTotalCount">0</span></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Sticky Search Box Container -->
            <div class="p-3 border-bottom" style="background-color: #fafafa;">
                <div class="position-relative">
                    <i class="fa fa-search lucide-search-icon"></i>
                    <input type="text" class="form-control lucide-search-input w-100" id="iconPickerSearch" placeholder="Search icons..." autocomplete="off">
                    <button class="btn btn-link text-muted position-absolute" type="button" id="clearIconSearch" style="display:none; right: 10px; top: 50%; transform: translateY(-50%); text-decoration: none;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="modal-body icon-picker-body p-4 pt-4">
                <!-- Icon Grid -->
                <div id="iconGrid" class="lucide-grid-container">
                    <!-- Icons will be loaded here dynamically -->
                    <div class="col-12 text-center py-5 w-100" style="grid-column: 1 / -1;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading icons...</p>
                    </div>
                </div>
                
                <div id="noIconsMessage" class="text-center py-5 d-none">
                    <h5 class="text-muted"><i class="fa fa-frown me-2"></i>No icons found.</h5>
                    <p class="text-muted">Try a different search term.</p>
                </div>
            </div>

            <div class="modal-footer bg-white border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <a href="https://lucide.dev/icons/" target="_blank" class="btn btn-primary text-white">
                    <i class="fa fa-external-link-alt me-1"></i> Official Website
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Toast for Copy -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1060">
    <div id="iconCopyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa fa-check-circle me-2"></i> <span id="iconCopyToastMsg">Icon name copied.</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script @cspNonce src="https://unpkg.com/lucide@latest"></script>
<script @cspNonce>
document.addEventListener('DOMContentLoaded', function () {
    let allIcons = [];
    let iconGrid = document.getElementById('iconGrid');
    let searchInput = document.getElementById('iconPickerSearch');
    let clearSearchBtn = document.getElementById('clearIconSearch');
    let noIconsMessage = document.getElementById('noIconsMessage');
    let lucideTotalCount = document.getElementById('lucideTotalCount');
    let modalEl = document.getElementById('lucideIconPickerModal');
    let copyToastEl = document.getElementById('iconCopyToast');
    let copyToast = typeof bootstrap !== 'undefined' ? new bootstrap.Toast(copyToastEl) : null;
    let loaded = false;

    // Load icons when modal is opened for the first time
    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function () {
            if (!loaded) {
                fetchIcons();
            }
            setTimeout(() => searchInput.focus(), 500);
        });
        
        modalEl.addEventListener('hidden.bs.modal', function () {
            // Optional: reset search when closed
            // searchInput.value = '';
            // filterIcons('');
        });
    }

    function fetchIcons() {
        fetch('{{ asset("assets/json/lucide-icons.json") }}')
            .then(response => response.json())
            .then(data => {
                allIcons = data;
                lucideTotalCount.textContent = allIcons.length;
                loaded = true;
                renderIcons(allIcons);
            })
            .catch(error => {
                console.error('Error fetching Lucide icons:', error);
                iconGrid.innerHTML = '<div class="col-12 text-center text-danger w-100" style="grid-column: 1 / -1;"><p>Failed to load icons.</p></div>';
            });
    }

    // Convert PascalCase/CamelCase to kebab-case for lucide data attribute
    function toKebabCase(str) {
        return str.replace(/([a-z0-9]|(?=[A-Z]))([A-Z])/g, '$1-$2').toLowerCase().replace(/^-/, '');
    }

    function renderIcons(iconsToRender) {
        if (iconsToRender.length === 0) {
            iconGrid.innerHTML = '';
            noIconsMessage.classList.remove('d-none');
            return;
        }

        noIconsMessage.classList.add('d-none');
        
        let html = '';
        iconsToRender.forEach(iconName => {
            const kebabName = toKebabCase(iconName);
            html += `
                <button type="button" class="icon-btn" data-icon="${iconName}">
                    <i data-lucide="${kebabName}" style="width: 24px; height: 24px; stroke-width: 2;"></i>
                </button>
            `;
        });

        iconGrid.innerHTML = html;
        
        // Re-initialize lucide icons for the newly added HTML
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons({
                root: iconGrid,
                nameAttr: 'data-lucide'
            });
        }
    }

    // Debounce search
    let timeout = null;
    function filterIcons(query) {
        query = query.toLowerCase().replace(/[\s-]/g, '');
        
        if (query === '') {
            clearSearchBtn.style.display = 'none';
            renderIcons(allIcons);
            return;
        }
        
        clearSearchBtn.style.display = 'block';
        
        const filtered = allIcons.filter(iconName => {
            return iconName.toLowerCase().replace(/[\s-]/g, '').includes(query);
        });
        
        renderIcons(filtered);
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                filterIcons(e.target.value);
            }, 300); // 300ms debounce
        });
        
        // Handle keyboard navigation inside search (Enter to select first if only 1, Esc to close)
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getInstance(modalEl).hide();
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const visibleCards = iconGrid.querySelectorAll('.icon-card');
                if (visibleCards.length === 1) {
                    const btnUse = visibleCards[0].querySelector('.btn-use');
                    if (btnUse) btnUse.click();
                }
            }
        });
    }

    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            filterIcons('');
            searchInput.focus();
        });
    }

    // Event delegation for icon click
    if (iconGrid) {
        iconGrid.addEventListener('click', function(e) {
            const btn = e.target.closest('.icon-btn');
            if (btn) {
                const iconName = btn.getAttribute('data-icon');
                
                // Copy to clipboard
                navigator.clipboard.writeText(iconName).then(() => {
                    document.getElementById('iconCopyToastMsg').innerHTML = `<strong>${iconName}</strong> copied & selected!`;
                    if (copyToast) copyToast.show();
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });

                // Set input value
                const iconInput = document.getElementById('icon_png_text');
                if (iconInput) {
                    iconInput.value = iconName;
                    // Trigger change event for any listeners
                    iconInput.dispatchEvent(new Event('change'));
                    
                    // Close modal
                    if (typeof bootstrap !== 'undefined') {
                        bootstrap.Modal.getInstance(modalEl).hide();
                    }
                }
            }
        });
    }
});
</script>

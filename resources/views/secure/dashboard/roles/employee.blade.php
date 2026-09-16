<div class="row g-4">
    <!-- Hero Banner with Glassmorphism & Gradient -->
    <div class="col-12">
        <div class="card border-0 shadow-lg overflow-hidden position-relative emp-hero-banner">
            <!-- Decorative Glass Bubbles -->
            <div class="emp-hero-bubble emp-hero-bubble-1"></div>
            <div class="emp-hero-bubble emp-hero-bubble-2"></div>
            <div class="emp-hero-bubble emp-hero-bubble-3"></div>

            <div class="card-body p-4 p-md-4 position-relative z-1">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2 emp-role-pill">
                            <span class="emp-pulse-dot"></span>
                            <span class="text-white fw-semibold small text-uppercase tracking-wider">CPCB Employee Dashboard</span>
                        </div>
                        <h2 class="text-white fw-bold mb-1 display-7 animate__animated animate__fadeIn">
                            Welcome, <span class="text-warning text-gradient-warning">{{ auth()->user()->name }}</span> 👋
                        </h2>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 emp-quick-info-box text-white">
                            <i class="ti ti-calendar-event fs-5 text-warning"></i>
                            <span class="small fw-semibold">{{ date('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Cards Partial (Internal Services + Internal Portals) -->
    @include('secure.dashboard.roles.partials.content_cards_employee')
</div>

<!-- Employee Custom Glassmorphism & Gradient Styles -->
<style @cspNonce>
    /* Hero Banner */
    .emp-hero-banner {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7928ca 100%);
        border-radius: 16px;
    }

    .emp-hero-bubble {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .emp-hero-bubble-1 {
        width: 320px;
        height: 320px;
        top: -120px;
        right: -60px;
    }

    .emp-hero-bubble-2 {
        width: 220px;
        height: 220px;
        bottom: -80px;
        left: -50px;
    }

    .emp-hero-bubble-3 {
        width: 150px;
        height: 150px;
        top: 20%;
        left: 45%;
        opacity: 0.5;
    }

    .emp-role-pill {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .emp-pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        animation: empPulse 2s infinite;
    }

    @keyframes empPulse {
        0% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        }
        70% {
            box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
        }
    }

    .emp-quick-info-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .emp-section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Glassmorphism Cards for Section 1 (Internal Services) */
    .emp-glass-card {
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.12);
    }

    .emp-glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 28px -4px rgba(0, 0, 0, 0.22);
    }

    .emp-glass-glow {
        position: absolute;
        width: 140px;
        height: 140px;
        top: -40px;
        right: -40px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .emp-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .emp-glass-card:hover .emp-icon-circle {
        transform: scale(1.08) rotate(-4deg);
    }

    .emp-card-pill {
        font-size: 0.72rem;
        padding: 5px 10px;
        border-radius: 30px;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .emp-action-btn {
        transition: all 0.25s ease;
        border: none;
    }

    .emp-glass-card:hover .emp-action-btn,
    .emp-glass-portal-card:hover .emp-action-btn {
        transform: translateX(3px);
    }

    /* Card Gradient Themes */
    .emp-card-circular {
        background: linear-gradient(135deg, #1e40af 0%, #2563eb 60%, #3b82f6 100%);
    }

    .emp-card-office {
        background: linear-gradient(135deg, #0369a1 0%, #0284c7 60%, #0ea5e9 100%);
    }

    .emp-card-memorandum {
        background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 60%, #8b5cf6 100%);
    }

    .emp-card-forms {
        background: linear-gradient(135deg, #047857 0%, #059669 60%, #10b981 100%);
    }

    .emp-card-medical {
        background: linear-gradient(135deg, #be123c 0%, #e11d48 60%, #f43f5e 100%);
    }

    /* Section 2: CPCBs Internal Portals Glassmorphism */
    .emp-glass-portal-card {
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.14);
    }

    .emp-glass-portal-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px -4px rgba(0, 0, 0, 0.24);
    }

    .emp-portal-glow {
        position: absolute;
        width: 150px;
        height: 150px;
        bottom: -50px;
        right: -50px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .emp-portal-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .emp-glass-portal-card:hover .emp-portal-icon-circle {
        transform: scale(1.08) rotate(4deg);
    }

    .emp-portal-pill {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 0.72rem;
        padding: 5px 10px;
        border-radius: 30px;
        font-weight: 600;
    }

    /* Internal Portal Gradients */
    .emp-portal-legal {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
    }

    .emp-portal-lms {
        background: linear-gradient(135deg, #78350f 0%, #92400e 60%, #b45309 100%);
    }

    .emp-portal-helpdesk {
        background: linear-gradient(135deg, #115e59 0%, #0f766e 60%, #14b8a6 100%);
    }

    .emp-portal-gatepass {
        background: linear-gradient(135deg, #334155 0%, #475569 60%, #64748b 100%);
    }

    .emp-portal-workload {
        background: linear-gradient(135deg, #581c87 0%, #701a75 60%, #86198f 100%);
    }
</style>
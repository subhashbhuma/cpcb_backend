<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm overflow-hidden"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px;">
            <div class="card-body p-5 text-center position-relative">

                <div class="position-absolute rounded-circle bg-white opacity-10"
                    style="width: 200px; height: 200px; top: -50px; left: -50px;"></div>
                <div class="position-absolute rounded-circle bg-white opacity-10"
                    style="width: 100px; height: 100px; bottom: -20px; right: 5%;"></div>

                <div class="animate__animated animate__fadeIn">
                    <div class="display-1 mb-3">👋</div>
                    <h1 class="text-white fw-bold mb-0">
                        Welcome, <span class="text-warning">{{ auth()->user()->name }}</span>&nbsp; !
                    </h1>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12">
        <p>
            This section is intended for internal communication and provides access to Circulars, Memorandums, and Office Orders issued by the Central Pollution Control Board (CPCB) from time to time for its employees.
            It also contains information related to the CPCB Medical Policy, which is updated periodically. Additionally this section also contains list of  CPCB`s internal portals for use of employees.
        </p>
        <p>
            Each category is divided into two sections:
        </p>
        <ul>
            <li>
                <b>Latest</b>: Displays information issued within the last three months.
            </li>
            <li>
                <b>Archival</b>: Contains information older than three months, which is moved from the Latest section for future reference.
            </li>
        </ul>
    </div>
<div class="row mt-4">
    @include('secure.dashboard.roles.partials.content_cards_employee')
</div>

<style @cspNonce>
    .animate__fadeIn {
        animation: fadeIn 1.2s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
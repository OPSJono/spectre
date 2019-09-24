@section('navigation')
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">Spectre</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item {{ Route::is('welcome') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('welcome') }}">Home
                        </a>
                    </li>
                    <li class="nav-item {{ Route::is('import') ? 'active' : null }}">
                        <a class="nav-link" href="{{ route('import') }}">Import CSV</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/OPSJono/Spectre" target="_blank">View Source on Github</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@show
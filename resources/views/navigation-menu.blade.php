<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <button id="sidebarCollapse" class="btn navbar-btn">
        <i class="fas fa-bars" style="color:white"></i>
    </button>
    <a class="navbar-brand" href="#">
        Agenda
    </a>
    <div class="col-lg-3 offset-lg-1 mb-0 text-right text-white plan-desktop">
        <!--<h3 class="mb-0">Agenda</h3>-->
    </div>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tareas">
                <a class="nav-link" href="{{ url('/dashboard') }}">Tareas</a>
            </li>
            @role('Gerente')
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Usuarios">
                <a class="nav-link" href="{{ route('usuarios') }}">Usuarios</a>
            </li>
            @endrole
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link"><i class="fa fa-fw fa-user"></i> usuario: <span class="badge badge-primary">{{auth()->user()->name}}</span></a>
            </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
                    <i class="fa fa-fw fa-sign-out-alt"></i>Cerrar Sesión
                </a>
                <form method="POST" id="logout-form" action="{{ route('logout') }}">
                    @csrf
                </form>
              </li>
        </ul>
      </div>
</nav>


<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                <li>
                    <!-- User Profile-->
                    <div class="user-profile d-flex no-block dropdown m-t-20">
                        <div class="user-pic"><img src="{{Auth::user()->image}}" alt="users" class="rounded-circle" width="40" /></div>
                        <div class="user-content hide-menu m-l-10" style="font-size: 0.75em;">
                            <a href="javascript:void(0)" class="" id="Userdd" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <h5 style="font-size: 1.5em;" class="m-b-0 user-name font-medium">{{ Auth::user()->name }}<i class="fa fa-angle-down"></i></h5>
                                <span class="op-5 user-email">{{ Auth::user()->email }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="Userdd">
                                <a class="dropdown-item" href="/profil" ><i class="ti-user m-r-5 m-l-5"></i> Mon Profil</a>
                                <a class="dropdown-item" href="{{route('facture.index')}}"><i class="ti-wallet m-r-5 m-l-5"></i> Facture</a>
                                <a class="dropdown-item" href="{{route('inbox.index')}}"><i class="ti-email m-r-5 m-l-5"></i> Inbox
                                    <span class="nbrNotify" style="
                                    left: 90px;
                                    top: 105px;
                                    position: absolute;
                                    " ><b>{{auth()->user()->unreadNotifications->count()}}</b></span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings m-r-5 m-l-5"></i> Parametre</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"  href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off m-r-5 m-l-5"></i>
                                    Deconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                     @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End User Profile-->
                </li>


                @can('edit-users')
                <li class="p-15 m-t-10"><a href="{{route('register')}}" class="btn btn-block create-btn text-white no-block d-flex align-items-center">
                    <i class="fa fa-plus-square"></i>
                    <span class="hide-menu m-l-5">Nouveau Utilisateur</span> </a></li>
                @endcan
                <!-- User Profile-->
                @can('client-admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                @endcan
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/profil" aria-expanded="false"><i class="mdi mdi-account-network"></i><span class="hide-menu">Profile</span></a></li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/commandes" aria-expanded="false"><i class="mdi mdi-package-variant"></i>

                        @can('manage-users')
                            <span class="hide-menu">Commandes</span>
                            <span class="badge badge-danger">{{App\Commande::where('statut','Reporté')->count()}}</span>
                        @endcan
                        @cannot('manage-users')
                        <span class="hide-menu">Gestion des commandes</span>
                    @endcan
                    </a>
                </li>
                @cannot('livreur')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('bonlivraison.index')}}" aria-expanded="false"><i class="mdi mdi-note-text"></i><span class="hide-menu">Bon de livraison</span></a></li>
                @can('client-admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('facture.index')}}" aria-expanded="false"><i class="mdi mdi-newspaper"></i><span class="hide-menu">Facture</span></a></li>

                @endcan

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('archive.index')}}" aria-expanded="false"><i class="mdi mdi-archive"></i><span class="hide-menu">Archive</span></a></li>

                @endcannot
                @can('livreur-admin')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                    href=
                    @can('edit-users')
                    "{{route('caisse.index')}}"
                    @endcan
                    @can('livreur')
                    "{{route('caisse.livreur',['id'=> Auth::user()->id])}}"
                    @endcan
                    aria-expanded="false"><i class="mdi mdi-cash-multiple"></i><span class="hide-menu">Caisse</span></a></li>
                @endcan

                @can('gestion-stock')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('produit.index')}}" aria-expanded="false"><i class="mdi mdi-package-variant-closed"></i><span class="hide-menu">Gestion du stock</span></a></li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('reception.index')}}" aria-expanded="false"><i class="mdi mdi-truck"></i>
                        <span class="hide-menu">
                            Envoie de Stock
                            @can('manage-users')
                            <span class="badge badge-danger">{{App\Reception::where('etat','Envoyé')->count()}}</span>
                            @endcan
                        </span>
                    </a>
                </li>
                @endcan
                @can('manage-users')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('Relance.index')}}" aria-expanded="false"><i class="mdi mdi-creation"></i><span class="hide-menu">Commandes VIP</span></a></li>
                @endcan
                @can('edit-users')
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('ville.index')}}" aria-expanded="false"><i class="mdi mdi-castle"></i><span class="hide-menu">Gestion des villes</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.users.index')}}" aria-expanded="false"><i class="mdi mdi-account-switch"></i><span class="hide-menu">Utilisateurs
                @if ($nouveau > 0)
                <span class="badge badge-danger">{{$nouveau}}</span>
                @endif
                </span></a></li>
                @endcan
                @can('delete-commande')
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('reclamation.index')}}" aria-expanded="false"><i class="fab fa-buffer"></i>
                        <span class="hide-menu">Reclamations
                            @can('manage-users')
                            <span class="badge badge-danger">{{App\Reclamation::where('etat',0)->count()}}</span>
                            @endcan
                        </span>
                    </a>
                </li>
                @endcan
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>





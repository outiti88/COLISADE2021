
@extends('racine')



@section('style')

<style>
.ct-label{
    height: 30px;
    width: 20px !important;
      /** Rotation */
      -webkit-transform: rotate(-25deg);
        -moz-transform: rotate(-25deg);
        transform:rotate(-25deg);

}


</style>

@endsection

@section('title')
    Dashboard
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-5">
            <h4 class="page-title">Dashboard</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Colisade</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">

    <div class="row">
        <div class="col-xl-3 col-md-3">
          <div class="card card-stats" class="d-inline-block">
            <!-- Card body -->
            <div class="card-body" >
              <div class="row">
                <div class="col">

                  <h5 class="card-title text-uppercase text-muted mb-0">Chiffre d'affaire NET</h5>
                  <span class="h2 font-weight-bold mb-0" >{{$ca}} DH</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow"  data-toggle="tooltip" data-placement="top" title="CA TOTAL des Colis Livrés">
                    <i class="ni ni-money-coins"></i>


                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                  @if ($caPercent<=0)
                  <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{$caPercent}}%</span>
                  @elseif($caPercent<10)
                  <span class="text-warning mr-2"><i class="fa fa-arrow-right"></i> {{$caPercent}}%</span>
                  @else
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$caPercent}}%</span>
                  @endif
                <span class="text-nowrap">Depuis le mois dernier</span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-3">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">
                      @can('edit-users')
                      Colis Livrés
                      @endcan
                      @can('fournisseur')
                      Montant facturé
                      @endcan

                    </h5>
                  <span class="h2 font-weight-bold mb-0">{{$caFacturer}} DH</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow" data-toggle="tooltip" data-placement="top" title="Montant facturé">
                    <i class="ni ni-active-40"></i>

                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2"><i class="fas fa-file-invoice-dollar"></i>  {{$caNonfacturer}} MAD</span>
                <span class="text-nowrap">
                    @can('edit-users')
                        Part des livreurs
                    @endcan

                    @can('fournisseur')
                    Restant à Facturer
                    @endcan

                    </span>
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-3">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                    @can('edit-users')
                    <h5 class="card-title text-uppercase text-muted mb-0">Colis Refusés</h5>
                    <span class="h2 font-weight-bold mb-0">{{$cmdRefuser}} MAD</span>
                    @endcan
                    @can('fournisseur')
                    <h5 class="card-title text-uppercase text-muted mb-0">Colis Non Livrés</h5>
                    <span class="h2 font-weight-bold mb-0">{{$cmdRefuser}} Colis</span>
                    @endcan

                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                    <i class="ni ni-chart-pie-35"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                  @can('edit-users')
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$cmdLivRefuser}} MAD</span>
                  <span class="text-nowrap">Part des livreurs</span>
                  @endcan
                  @can('fournisseur')
                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$cmdLivRefuser}} %</span>
                  <span class="text-nowrap">Des Colis</span>
                  @endcan

              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-md-3">
          <div class="card card-stats">
            <!-- Card body -->
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Colis du jour</h5>
                  <span class="h2 font-weight-bold mb-0">{{$todayCmd}} Colis</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                    <i class="ni ni-chart-bar-32"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$lastdayCmd}} Colis</span>
                <span class="text-nowrap">Colis d'hier</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="row ">
        <div class="col-xl-3 col-lg-6">
          <div class="card l-bg-green">
            <div class="card-statistic-3">
              <div class="card-icon card-icon-large"><i class="fa fa-award"></i></div>
              <div class="card-content">
                <h4 class="card-title">Colis Livrés</h4>
                <span style="font-weight: bold; font-size: 1.75em;" >{{$tabTotal['livré']['nbr']}}</span>
                <div class="progress mt-1 mb-1" data-height="8">
                  <div class="progress-bar l-bg-purple" role="progressbar" data-width="{{$tabTotal['livré']['percentage']}}%" aria-valuenow="{{$tabTotal['livré']['percentage']}}"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mb-0 text-sm">
                  <span class="mr-2"><i class="fa fa-arrow-up"></i> {{$tabTotal['livré']['percentage']}}%</span>
                  <span class="text-nowrap">De tous les Colis</span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6">
          <div class="card l-bg-cyan">
            <div class="card-statistic-3">
              <div class="card-icon card-icon-large"><i class="fas fa-hourglass-half"></i></div>
              <div class="card-content">
                <h4 class="card-title">En cours de livraison</h4>
                <span style="font-weight: bold; font-size: 1.75em;" >{{$tabTotal['en_cours']['nbr']}}</span>
                <div class="progress mt-1 mb-1" data-height="8">
                  <div class="progress-bar l-bg-orange" role="progressbar" data-width="{{$tabTotal['en_cours']['percentage']}}%" aria-valuenow="{{$tabTotal['en_cours']['percentage']}}"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mb-0 text-sm">
                  <span class="mr-2"><i class="fa fa-arrow-up"></i> {{$tabTotal['en_cours']['percentage']}}%</span>
                  <span class="text-nowrap">De tous les Colis</span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6">
          <div class="card l-bg-purple">
            <div class="card-statistic-3">
              <div class="card-icon card-icon-large"><i class="fas fa-street-view"></i></div>
              <div class="card-content">
                <h4 class="card-title">Colis refusés</h4>
                <span style="font-weight: bold; font-size: 1.75em;" >{{$tabTotal['refusé']['nbr']}}</span>
                <div class="progress mt-1 mb-1" data-height="8">
                  <div class="progress-bar l-bg-cyan" role="progressbar" data-width="{{$tabTotal['refusé']['percentage']}}%" aria-valuenow="{{$tabTotal['refusé']['percentage']}}"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mb-0 text-sm">
                  <span class="mr-2"><i class="fa fa-arrow-up"></i> {{$tabTotal['refusé']['percentage']}}%</span>
                  <span class="text-nowrap">De tous les Colis</span>
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6">
          <div class="card l-bg-orange">
            <div class="card-statistic-3">
              <div class="card-icon card-icon-large"><i class="fas fa-history"></i></div>
              <div class="card-content">
                <h4 class="card-title">Colis reportés</h4>
                <span style="font-weight: bold; font-size: 1.75em;" >{{$tabTotal['reporté']['nbr']}}</span>
                <div class="progress mt-1 mb-1" data-height="8">
                  <div class="progress-bar l-bg-green" role="progressbar" data-width="{{$tabTotal['reporté']['percentage']}}%" aria-valuenow="{{$tabTotal['reporté']['percentage']}}"
                    aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="mb-0 text-sm">
                  <span class="mr-2"><i class="fa fa-arrow-up"></i> {{$tabTotal['reporté']['percentage']}}%</span>
                  <span class="text-nowrap">De tous les Colis</span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- ============================================================== -->
    <!-- Sales chart -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Statistique de chiffre d'affaire par mois</h4>
                            <h5 class="card-subtitle">Les Colis livrés et les Non livrés</h5>
                        </div>
                        <div class="ml-auto d-flex no-block align-items-center">
                            <ul class="list-inline font-12 dl m-r-15 m-b-0">
                                <li class="list-inline-item text-info"><i class="fa fa-circle"></i> Livré</li>
                                <li class="list-inline-item text-primary"><i class="fa fa-circle"></i> Non Livré</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <!-- column -->
                        <div class="col-lg-12">
                            <div class="campaign ct-charts"></div>
                        </div>
                        <!-- column -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Statuts des Colis pour Aujourd'hui</h4>
                    <div class="feed-widget">
                        <ul class="list-style-none feed-body m-0 p-b-20">
                            <li class="feed-item">
                                <div class="feed-icon bg-warning"><i class="ti-shopping-cart"></i></div> {{$tab['expidie']['nbr']}} Colis <br> Expédiés. <span class="ml-auto font-12 text-muted">{{$tab['expidie']['date']}}</span></li>
                            <li class="feed-item">
                                <div class="feed-icon bg-info"><i class="mdi mdi-truck"></i></div> {{$tab['en_cours']['nbr']}} Colis <br> En Cours de livraison.<span class="ml-auto font-12 text-muted">{{$tab['en_cours']['date']}}</span></li>
                            <li class="feed-item">
                                <div class="feed-icon bg-success"><i class="mdi mdi-checkbox-marked-outline"></i></div> {{$tab['livré']['nbr']}} Colis <br> Livrés.<span class="ml-auto font-12 text-muted">{{$tab['livré']['date']}}</span></li>
                            <li class="feed-item">
                                <div class="feed-icon bg-danger"><i class="mdi mdi-tumblr-reblog"></i></div> {{$tab['retour']['nbr']}} Colis <br> NON Livrés.<span class="ml-auto font-12 text-muted">{{$tab['retour']['date']}}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                  <h4>NOMBRE DE COLIS PAR MOIS</h4>
                </div>
                <div class="card-body">
                  <div class="recent-report__chart">
                    <div id="chart3"></div>
                  </div>
                </div>
              </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h4>NOMBRE DE COLIS PAR JOUR</h4>
            </div>
            <div class="card-body">
              <div class="recent-report__chart">
                <div id="chart2"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- ============================================================== -->
     <div class="row">
        <!-- column -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- title -->
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Top client</h4>
                            <h5 class="card-subtitle">Vos 5 meilleurs clients</h5>
                        </div>

                    </div>
                    <!-- title -->
                </div>
                <div class="table-responsive">
                    <table class="table v-middle">
                        <thead class="thead-light">
                            <tr>

                                <th class="border-top-0">Nom du client</th>
                                @can('ramassage-commande')
                                <th class="border-top-0">Date de création</th>
                                <th class="border-top-0">Nombre de Commmande</th>
                                <th class="border-top-0">Colis Livrés</th>
                                @endcan
                                @cannot('ramassage-commande')
                                <th class="border-top-0">Nombre de Colis</th>
                                <th class="border-top-0">Nombre de colis</th>
                                <th class="border-top-0">Montant</th>
                                @endcannot

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topCmds as $index => $topCmd)
                            <tr>
                                @can('ramassage-commande')
                                <td>
                                    <div class="d-flex align-items-center">
                                    <div class="m-r-10">
                                        <a title="{{$users[$index]->name}}" class=" text-muted waves-effect waves-dark pro-pic"

                                                @can('edit-users')
                                                    href="{{route('admin.users.edit',$users[$index]->id)}}"
                                                @endcan

                                        >
                                        <img src="{{$users[$index]->image}}" alt="user" class="rounded-circle" width="31">
                                    </a>
                                    </div>
                                    <div>
                                        <h4 class="m-b-0 font-16">{{$users[$index]->name}}</h4>
                                    </div>
                                </div>
                                </td>
                                <td>
                                    <h5 class="m-b-0 font-16">{{$users[$index]->created_at}}</h5>
                                </td>

                                <td>{{$topCmd->cmd}}</td>
                                <td>
                                    <h5 class="m-b-0">{{$topCmdLivr[$index]->cmd}}</h5>
                                </td>
                                @endcan
                                @cannot('ramassage-commande')
                                <td>
                                            <h5 class="m-b-0 font-16">{{$topCmd->nom}}</h5>
                                </td>

                                <td>{{$topCmd->cmd}}</td>
                                <td>{{$topCmd->colis}}</td>
                                <td>
                                    <h5 class="m-b-0">{{$topCmd->m}} MAD</h5>
                                </td>
                                @endcannot
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




@endsection

@section('javascript')
<script src="{{url('/assets/libs/chartist/dist/chartist.min.js')}}"></script>
<script src="{{url('/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
<script src="{{url('/otika/assets/bundles/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{ url('/otika/assets/js/custom.js') }}"></script>


<script>
    'use strict';
$(function () {
    chart1();
    chart2();
    chart3();
    chart4();
    chart5();
    chart6();
    chart7();
    chart8();
});

function arrayMin(arr) {
  return arr.reduce(function (p, v) {
    return ( p < v ? p : v );
  });
}

function arrayMax(arr) {
  return arr.reduce(function (p, v) {
    return ( p > v ? p : v );
  });
}

function chart1() {
    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded',
                columnWidth: '55%',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [{
            name: 'Net Profit',
            data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
        }, {
            name: 'Revenue',
            data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
        }, {
            name: 'Free Cash Flow',
            data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
        }],
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            labels: {
                style: {
                    colors: '#9aa0ac',
                }
            }
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            },
            labels: {
                style: {
                    color: '#9aa0ac',
                }
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands"
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart1"),
        options
    );

    chart.render();


}

function chart2() {

    var  arrayChart2 = <?php echo $chart2; ?> ;
    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    position: 'top', // top, center, bottom
                },
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val + "%";
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#9aa0ac"]
            }
        },
        series: [{
            name: 'Nombre de Colis',
            data: arrayChart2
        }],
        xaxis: {
            categories: ["Dim","Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
            position: 'top',
            labels: {
                offsetY: -18,
                style: {
                    colors: '#9aa0ac',
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            crosshairs: {
                fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                }
            },
            tooltip: {
                enabled: true,
                offsetY: -35,

            }
        },
        fill: {
            gradient: {
                shade: 'light',
                type: "horizontal",
                shadeIntensity: 0.25,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [50, 0, 100, 100]
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                formatter: function (val) {
                    return val + "%";
                }
            },
            max: arrayMax(arrayChart2)+5,
            min: 0

        },
        title: {
            text: 'Pourcentage de nombre de colis ajoutés chaque jour',
            floating: true,
            offsetY: 320,
            align: 'center',
            style: {
                color: '#9aa0ac'
            }
        },
    }

    var chart = new ApexCharts(
        document.querySelector("#chart2"),
        options
    );

    chart.render();

}
function chart3() {

    var livrer =  <?php echo $livrerChart1; ?> ;
    var nonLivrer = <?php echo $nonLivrerChart1; ?> ;



    var options = {
        chart: {
            height: 350,
            type: 'line',
            shadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 1
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#77B6EA', '#545454'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth'
        },
        series: [{
            name: "Livrés",
            data: livrer
        },
        {
            name: "Non Livrés",
            data: nonLivrer
        }
        ],
        title: {
            text: 'Quantités des Colis livrés et non livrés',
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {

            size: 6
        },
        xaxis: {
            categories: ['Jan', 'Fev', 'Mar', 'Avr', 'May', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
            title: {
                text: 'Mois'
            },
            labels: {
                style: {
                    colors: '#9aa0ac',
                }
            }
        },
        yaxis: {
            title: {
                text: 'Quantité'
            },
            labels: {
                style: {
                    color: '#9aa0ac',
                }
            },
            max: arrayMax(livrer)+5,
            min: arrayMin(nonLivrer)
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart3"),
        options
    );

    chart.render();
}
function chart4() {
    var options = {
        chart: {
            height: 350,
            type: 'line',
            shadow: {
                enabled: false,
                color: '#bbb',
                top: 3,
                left: 2,
                blur: 3,
                opacity: 1
            },
        },
        stroke: {
            width: 7,
            curve: 'smooth'
        },
        series: [{
            name: 'Likes',
            data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5]
        }],
        xaxis: {
            type: 'datetime',
            categories: ['1/11/2000', '2/11/2000', '3/11/2000', '4/11/2000', '5/11/2000', '6/11/2000', '7/11/2000', '8/11/2000', '9/11/2000', '10/11/2000', '11/11/2000', '12/11/2000', '1/11/2001', '2/11/2001', '3/11/2001', '4/11/2001', '5/11/2001', '6/11/2001'],
            labels: {
                style: {
                    colors: '#9aa0ac',
                }
            }
        },
        title: {
            text: 'Social Media',
            align: 'left',
            style: {
                fontSize: "16px",
                color: '#666'
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#FDD835'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
            size: 4,
            opacity: 0.9,
            colors: ["#FFA41B"],
            strokeColor: "#fff",
            strokeWidth: 2,

            hover: {
                size: 7,
            }
        },
        yaxis: {
            min: -10,
            max: 40,
            title: {
                text: 'Engagement',
            },
            labels: {
                style: {
                    color: '#9aa0ac',
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart4"),
        options
    );

    chart.render();
}
function chart5() {
    var options = {
        chart: {
            height: 350,
            type: 'line',
        },
        series: [{
            name: 'Website Blog',
            type: 'column',
            data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
        }, {
            name: 'Social Media',
            type: 'line',
            data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
        }],
        stroke: {
            width: [0, 4]
        },
        title: {
            text: 'Traffic Sources'
        },
        // labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
        xaxis: {
            type: 'datetime',
            labels: {
                style: {
                    colors: '#9aa0ac',
                }
            }
        },
        yaxis: [{
            title: {
                text: 'Website Blog',
            },
            labels: {
                style: {
                    color: '#9aa0ac',
                }
            }

        }, {
            opposite: true,
            title: {
                text: 'Social Media'
            },
            labels: {
                style: {
                    color: '#9aa0ac',
                }
            }
        }]

    }

    var chart = new ApexCharts(
        document.querySelector("#chart5"),
        options
    );

    chart.render();
}
function chart6() {
    var options = {
        chart: {
            height: 350,
            type: 'area',
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        series: [{
            name: 'series1',
            data: [31, 40, 28, 51, 42, 109, 100]
        }, {
            name: 'series2',
            data: [11, 32, 45, 32, 34, 52, 41]
        }],

        xaxis: {
            type: 'datetime',
            categories: ["2018-09-19T00:00:00", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00", "2018-09-19T04:30:00", "2018-09-19T05:30:00", "2018-09-19T06:30:00"],
            labels: {
                style: {
                    colors: '#9aa0ac',
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    color: '#9aa0ac',
                }
            }

        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chart6"),
        options
    );

    chart.render();
}
function chart7() {
    var options = {
        chart: {
            width: 360,
            type: 'pie',
        },
        labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
        series: [44, 55, 13, 43, 22],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    }

    var chart = new ApexCharts(
        document.querySelector("#chart7"),
        options
    );

    chart.render();
}
function chart8() {

    var options = {
        chart: {
            height: 350,
            type: 'radar',
            dropShadow: {
                enabled: true,
                blur: 1,
                left: 1,
                top: 1
            }
        },
        series: [{
            name: 'Series 1',
            data: [80, 50, 30, 40, 100, 20],
        }, {
            name: 'Series 2',
            data: [20, 30, 40, 80, 20, 80],
        }, {
            name: 'Series 3',
            data: [44, 76, 78, 13, 43, 10],
        }],
        title: {
            text: 'Radar Chart - Multi Series'
        },
        stroke: {
            width: 0
        },
        fill: {
            opacity: 0.4
        },
        markers: {
            size: 0
        },
        labels: ['2011', '2012', '2013', '2014', '2015', '2016']
    }

    var chart = new ApexCharts(
        document.querySelector("#chart8"),
        options
    );

    chart.render();

    function update() {

        function randomSeries() {
            var arr = []
            for (var i = 0; i < 6; i++) {
                arr.push(Math.floor(Math.random() * 100))
            }

            return arr
        }


        chart.updateSeries([{
            name: 'Series 1',
            data: randomSeries(),
        }, {
            name: 'Series 2',
            data: randomSeries(),
        }, {
            name: 'Series 3',
            data: randomSeries(),
        }])
    }


}

</script>


<script>
    $(function() {
    "use strict";
    // ==============================================================
    // Newsletter
    // ==============================================================

    var livre =  <?php echo $livre; ?> ;
    var retour = <?php echo $retour; ?> ;

   // console.log("heeeeeeeeeeeeeey",livre );
    var chart = new Chartist.Line('.campaign', {
        labels: ['Jan', 'Fev', 'Mar', 'Avr', 'May', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
        series: [ livre, retour]

    }, {
        low: 0,
        high: livre,

        showArea: true,
        fullWidth: true,
        plugins: [
            Chartist.plugins.tooltip()
        ],
        axisY: {
            onlyInteger: true,
            scaleMinSpace: 30,
            offset: 20,
            labelInterpolationFnc: function(value) {
                return (value / 1) + '';
            }
        },

    });

    // Offset x1 a tiny amount so that the straight stroke gets a bounding box
    // Straight lines don't get a bounding box
    // Last remark on -> http://www.w3.org/TR/SVG11/coords.html#ObjectBoundingBox
    chart.on('draw', function(ctx) {
        if (ctx.type === 'area') {
            ctx.element.attr({
                x1: ctx.x1 + 0.001
            });
        }
    });

    // Create the gradient definition on created event (always after chart re-render)
    chart.on('created', function(ctx) {
        var defs = ctx.svg.elem('defs');
        defs.elem('linearGradient', {
            id: 'gradient',
            x1: 0,
            y1: 1,
            x2: 0,
            y2: 0
        }).elem('stop', {
            offset: 0,
            'stop-color': 'rgba(255, 255, 255, 1)'
        }).parent().elem('stop', {
            offset: 1,
            'stop-color': 'rgba(64, 196, 255, 1)'
        });
    });


    var chart = [chart];
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

@endsection

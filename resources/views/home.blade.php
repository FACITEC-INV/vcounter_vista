@extends('paginaBase')

@section('contenido')

    <h2>Estadística general</h2>
    <div class="card p-3">

        <div class="card-body row justify-content-center">
            <div class="col-4">
                <div class="shadow bg-body-tertiary rounded mt-4">
                    <div class="card-body">
                        <div class="card-title h6">
                            HOY - {{now()->format('d-m-Y')}}
                        </div>
                        <hr>
                        <div class="card-text">
                        <h4>
                            <small class="text-body-secondary">Automóviles:</small>
                            <span id="cards">{{ $carsHoy }}</span>
                        </h4>
                        <h4>
                            <small class="text-body-secondary">Camiones:</small>
                            <span id="cards">{{ $trucksHoy }}</span>
                        </h4>
                        </div>
                    </div>
                </div>
                <div class="shadow bg-body-tertiary rounded mt-4">
                    <div class="card-body">
                        <div class="card-title h6">
                            {{$mesAnho}}
                        </div>
                        <hr>
                        <div class="card-text">
                        <h4>
                            <small class="text-body-secondary">Automóviles:</small>
                            <span id="cards">{{$carsMes}}</span>
                        </h4>
                        <h4>
                            <small class="text-body-secondary">Camiones:</small>
                            <span id="cards">{{$trucksMes}}</span>
                        </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="row g-3 justify-content-center align-items-center">
                    <div class="col-auto">
                        <label class="col-form-label" for="inp-desde">Desde:</label>
                    </div>
                    <div class="col-auto">
                        <div
                            class="text-danger fw-light"
                            style="font-size: 14px;"
                            id="inp-desde-invalid">
                        </div>
                        <input
                            type="date"
                            class="form-control"
                            id="inp-desde" />
                    </div>
                    <div class="col-auto">
                        <label class="col-form-label" for="inp-hasta">Hasta:</label>
                    </div>
                    <div class="col-auto">
                        <div
                            class="text-danger fw-light"
                            style="font-size: 14px;"
                            id="inp-hasta-invalid">
                        </div>
                        <input
                            type="date"
                            class="form-control"
                            id="inp-hasta" />
                    </div>
                    <div class="col-auto">
                        <button
                            id="btn-process"
                            class="btn btn-sm btn-outline-secondary"
                            data-bs-toggle="tooltip"
                            data-bs-title="Procesar datos">
                            <img src="{{url('/img/process.png')}}" />
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="shadow bg-body-tertiary rounded p-4">
                            <canvas id="count"></canvas>
                    </div>
                </div>
            </div>
        </div>


    </div>



    <script src="{{url('/js/chart_js_v4.3.0.js')}}"></script>
    <script src="{{url('/js/dayjs_v1.11.7.js')}}"></script>
    <script>
        const ctx = document.getElementById('count');
        document.querySelector('a.inicio').classList.add('fw-semibold');
        const inDesde = document.getElementById('inp-desde');
        const inDesdeInv = document.getElementById('inp-desde-invalid');
        inDesde.value = dayjs().subtract(1, 'month').format('YYYY-MM-DD');
        const inHasta = document.getElementById('inp-hasta');
        inHasta.value = dayjs().format('YYYY-MM-DD');
        const btn = document.getElementById('btn-process');
        let conf = {};

        btn.addEventListener('click', procesar);

        async function procesar(){
            await validaFechas();
            if(!conf.fechasValidas)
                return;
            await getData();
            await loadData();
        }

        async function validaFechas(){
            const fdesde = dayjs(inDesde.value);
            const fhasta = dayjs(inHasta.value);
            if(fdesde.isAfter(fhasta)){
                inDesdeInv.innerHTML='Fecha inválida';
                return {...conf, fechasValidas: false}
            }
            const ydiff = fhasta.diff(fdesde, 'y');
            const mdiff = fhasta.diff(fdesde, 'M');
            const ddiff = fhasta.diff(fdesde, 'd');
            if(ydiff > 1){
                conf = {...conf, fechasValidas: true, vista: 'AÑO', fdesde, fhasta}
                return ;
            }
            if(mdiff > 1){
                conf = {...conf, fechasValidas: true, vista: 'MES', fdesde, fhasta}
                return ;
            }
            conf = {...conf, fechasValidas: true, vista: 'DIA', fdesde, fhasta}
            return ;
        }

        async function getData(){
            const fdesde = conf.fdesde.format('YYYY-MM-DD');
            const fhasta = conf.fhasta.format('YYYY-MM-DD');
            try {
                const res = await fetch('http://127.0.0.1:8000/api/detections/get',{
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({desde: fdesde, hasta: fhasta})
                });
                const data = await res.json();
                if(!data.ok){
                    throw new Error('Algo salió mal');
                    return ;
                }
                conf = {...conf, data:[...data.data]};
                return ;
            } catch (err) {
                console.log(err);
                return ;
            }
        }


        async function loadData(){
            const {data, vista} = conf;
            chart.data.labels = data.map(i => i.label);
            chart.data.datasets[0] = {
                data: data.map(i=>i.cant),
                label: 'CANTIDAD DE VEHÍCULOS POR ' + vista
            };

            chart.update();
            console.log(chart)
        }

        procesar();

        const chart = new Chart(ctx,{
            type: 'line',
        });

    </script>
@endsection

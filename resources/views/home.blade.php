@extends('paginaBase')

@section('contenido')

    <h2>Estadística general</h2>
    <div class="card">

        <div class="row justify-content-center">
            <div class="col-4">
                <div class="row pt-3 mb-2">
                    <div class="col-auto">
                        <label class="col-form-label" for="inp-date">Consultar fecha:</label>
                    </div>
                    <div class="col">
                        <input
                            type="date"
                            class="form-control"
                            id="inp-date" />
                    </div>
                </div>
            <div class="row">
                <div class="col">
                    <div class="shadow bg-body-tertiary rounded mt-2">
                        <div class="card-body">
                            <div class="card-title h6">
                                <small
                                    class="text-body-secondary"
                                    style="font-size: 12px;">Día: </small>
                                <span id="span-dia"></span>
                            </div>
                            <hr>
                            <div class="card-text">
                                <h6>
                                    <small class="text-body-secondary">Vehículos:</small>
                                    <span id="span-vehicDia"></span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="shadow bg-body-tertiary rounded mt-2">
                        <div class="card-body">
                            <div class="card-title h6">
                                <small
                                    class="text-body-secondary"
                                    style="font-size: 12px;">Mes: </small>
                                <span id="span-mes"></span>
                            </div>
                            <hr>
                            <div class="card-text">
                                <h6>
                                    <small class="text-body-secondary">Vehículos:</small>
                                    <span id="span-vehicMes"></span>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="shadow bg-body-tertiary rounded mt-5">
                    <div class="card-body">
                        <div class="card-title h6">
                            <small
                                class="text-body-secondary"
                                style="font-size: 12px;">Año: </small>
                            <span id="span-anho"></span>
                        </div>
                        <hr>
                        <div class="card-text">
                            <h5>
                                <small class="text-body-secondary">Vehículos:</small>
                                <span id="span-vehicAnho"></span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="row g-3 pt-3 justify-content-center align-items-center">
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
                <div class="pt-3 mb-3">
                    <div class="shadow bg-body-tertiary rounded p-4">
                            <canvas id="count"></canvas>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <script src="{{url('/js/chart_js_v4.3.0.js')}}"></script>
    <script src="{{url('/js/dayjs_v1.11.7.js')}}"></script>
    <script src="{{url('/js/contadores.js')}}"></script>
    <script src="{{url('/js/grafico.js')}}"></script>
@endsection

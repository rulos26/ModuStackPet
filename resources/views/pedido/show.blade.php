@extends('layouts.app')

@section('template_title')
    {{ $pedido->name ?? __('Show') . " " . __('Pedido') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Pedido</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('pedidos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Cliente:</strong>
                                    {{ $pedido->cliente }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Producto:</strong>
                                    {{ $pedido->producto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ciclo:</strong>
                                    {{ $pedido->ciclo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Pedido:</strong>
                                    {{ $pedido->estado_pedido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado Deuda:</strong>
                                    {{ $pedido->estado_deuda }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $pedido->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor Catalogo:</strong>
                                    {{ $pedido->valor_catalogo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor Lista:</strong>
                                    {{ $pedido->valor_lista }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

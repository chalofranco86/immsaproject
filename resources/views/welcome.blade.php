@extends('layouts.guest')

@section('title', 'Sistema de Órdenes de Trabajo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="card">
            <div class="card-body py-5">
                <h1 class="display-4 text-primary mb-4">
                    <i class="fas fa-tools"></i> Sistema de Órdenes
                </h1>
                <p class="lead mb-4">Sistema de gestión de órdenes de trabajo para talleres mecánicos</p>
                
                <div class="row mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
                                <h5>Órdenes de Trabajo</h5>
                                <p>Gestiona todas las órdenes de trabajo</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-users fa-3x text-success mb-3"></i>
                                <h5>Propietarios</h5>
                                <p>Administra los propietarios de vehículos</p>
                                <a href="{{ route('login') }}" class="btn btn-success">Iniciar Sesión</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="fas fa-user-tie fa-3x text-info mb-3"></i>
                                <h5>Empleados</h5>
                                <p>Gestiona el personal del taller</p>
                                <a href="{{ route('login') }}" class="btn btn-info">Iniciar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p>¿No tienes una cuenta? <a href="{{ route('login') }}">Contacta al administrador</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
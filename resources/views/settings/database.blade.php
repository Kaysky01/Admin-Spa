@extends('layouts.app')

@section('title', 'Database Information')
@section('page-title', 'Database Information')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-database me-2"></i>Database: {{ $dbName }}
                </h5>
                <a href="{{ route('settings.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Tabel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tables as $index => $table)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <i class="fas fa-table me-2 text-primary"></i>
                                    <strong>{{ array_values((array)$table)[0] }}</strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <h2>Detail Kendaraan</h2>
    <p><strong>No Polisi:</strong> {{ $kendaraan->no_polisi }}</p>
    <p><strong>Tipe:</strong> {{ $kendaraan->tipe }}</p>
    <p><strong>Merek:</strong> {{ $kendaraan->merek }}</p>
    <p><strong>Tahun:</strong> {{ $kendaraan->tahun }}</p>
    <p><strong>Customer:</strong> {{ $kendaraan->customer->nama }}</p>
@endsection

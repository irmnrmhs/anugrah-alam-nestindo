@php
    $title = 'Kelola Bahan Baku';
    $singular = 'Bahan Baku';
    $hideAddButton = true;
    $hideActions = true;
@endphp

@extends('layouts.form')

@php
    $title = 'Kelola Bahan Baku';
    $singular = 'Bahan Baku';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Biji</th>
    <th>Berat</th>
    <th>Biji Sisa</th>
    <th>Berat Sisa</th>
@stop

@section('table-body')
    @foreach($raw_materials as $index => $raw_material)
        <tr data-id="{{ $raw_material->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $raw_material->kode }}</td>
            <td>{{ $raw_material->biji }}</td>
            <td>{{ $raw_material->berat }}</td>
            <td>{{ $raw_material->biji_sisa }}</td>
            <td>{{ $raw_material->berat_sisa }}</td>
        </tr>
    @endforeach
@stop
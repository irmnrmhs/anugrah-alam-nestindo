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
@stop

@section('table-body')
    @foreach($raw_materials as $index => $raw_material)
        <tr data-id="{{ $raw_material->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $raw_material->kode }}</td>
            <td>{{ $raw_material->biji }}</td>
            <td>{{ $raw_material->berat }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop
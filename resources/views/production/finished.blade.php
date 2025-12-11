@php
    $title = 'Produk Jadi';
    $singular = 'Produk Jadi';
    $hideAddButton = true;
    $hideActions = true;
@endphp

@extends('layouts.form')

@php
    $title = 'Produk Jadi';
    $singular = 'Produk Jadi';
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
    @foreach($fproducts as $index => $fproduct)
        <tr data-id="{{ $fproduct->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $fproduct->kode }}</td>
            <td>{{ $fproduct->biji }}</td>
            <td>{{ $fproduct->berat }}</td>
            <td>{{ $fproduct->biji_sisa }}</td>
            <td>{{ $fproduct->berat_sisa }}</td>
        </tr>
    @endforeach
@stop
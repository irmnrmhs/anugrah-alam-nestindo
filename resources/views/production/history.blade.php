@php
    $title = 'Tracker';
    $singular = 'Tracker';
    $hideAddButton = true;
    $hideActions = true;
@endphp

@extends('layouts.form')

@php
    $title = 'Tracker';
    $singular = 'Tracker';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode Produk</th>
    <th>Asal</th>
    <th>Tujuan</th>
    <th>Total Biji</th>
    <th>Total Berat</th>
    <th>Sisa Biji</th>
    <th>Sisa Berat</th>
@stop

@section('table-body')
    @foreach($histories as $index => $history)
        <tr data-id="{{ $history->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $history->identifier->kode }}</td>
            <td>{{ $history->asal }}</td>
            <td>{{ $history->tujuan }}</td>
            <td>{{ $history->biji }}</td>
            <td>{{ $history->berat }}</td>
            <td>{{ $history->sisa_biji_sesek }}</td>
            <td>{{ $history->sisa_berat_sesek }}</td>
        </tr>
    @endforeach
@stop
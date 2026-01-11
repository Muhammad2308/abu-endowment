@extends('layouts.charifit')

@section('title', 'Charifit')

@section('body')
    <livewire:home.header-section />
    <livewire:home.auth-modal />

    <livewire:home.project-donations />
    
   
    <livewire:home.footer-area />
@endsection

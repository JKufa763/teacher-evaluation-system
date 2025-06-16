@extends('layouts.app')

@section('content')
<div class="container">
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
            Go to Admin Dashboard
        </a>
    @endif
</div>
@endsection
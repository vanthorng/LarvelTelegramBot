<!-- resources/views/telegram/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>File Details</h2>

    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>ID:</strong> {{ $file->id }}</li>
        <li class="list-group-item"><strong>Original Name:</strong> {{ $file->original_name }}</li>
        <li class="list-group-item"><strong>MIME Type:</strong> {{ $file->mime_type }}</li>
        <li class="list-group-item"><strong>Path:</strong> {{ $file->file_path }}</li>
        <li class="list-group-item">
            <strong>Link:</strong>
            <a href="{{ Storage::disk('s3')->url($file->file_path) }}" target="_blank">Open File</a>
        </li>
    </ul>

    <a href="{{ route('telegram.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection

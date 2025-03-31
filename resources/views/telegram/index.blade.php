<!-- resources/views/telegram/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Telegram Files</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('telegram.upload') }}" class="btn btn-primary mb-3">Upload New File</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Original Name</th>
                <th>MIME Type</th>
                <th>File</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($files as $file)
            <tr>
                <td>{{ $file->id }}</td>
                <td>{{ $file->original_name }}</td>
                <td>{{ $file->mime_type }}</td>
                <td>
                    <a href="{{ Storage::disk('s3')->url($file->file_path) }}" target="_blank">View</a>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('telegram.show', $file->id) }}" class="btn btn-sm btn-info">Show</a>

                        <form action="{{ route('telegram.destroy', $file->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this file?')">Delete</button>
                        </form>

                        <form action="{{ route('telegram.send', $file->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-success">Send to Telegram</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

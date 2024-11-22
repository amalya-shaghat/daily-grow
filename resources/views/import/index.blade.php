@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-4 bg-white shadow rounded">
        <h1 class="text-2xl font-bold mb-4">Импорт выписки из 1С</h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="file" class="block font-bold mb-2">Выберите файл (.txt)</label>
                <input type="file" name="file" id="file" class="block w-full border rounded p-2">
                @error('file')
                <div class="text-red-500 mt-2">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Загрузить</button>
        </form>
    </div>
@endsection

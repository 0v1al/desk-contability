@extends('layouts.app')

@section('content')
	<div class="container d-flex justify-content-center align-items-center flex-column">
		<h1 class="text-uppercase mr-auto">Încarcă fișiere clienți</h1>
		<p class="mr-auto mt-2">Aici poți încărca fișiere pentru anumiți clienți la alegere</p>
		<form
            class="w-50 p-5 shadow-sm border mt-4 file-form"
            action="{{ route("admin.upload_user_file") }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
            	<label>Alegeți clientul dorit</label>
            	<select name="user-id" class="form-control">
            		<option value="" selected>Alege un client...</option>
            		@if (count($users) > 0)
            			@foreach ($users as $user)
            				<option value="{{ $user->id }}">{{ $user->name }}</option>
            			@endforeach
            		@endif
            	</select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlFile1">Adăugați fișierul pe care doriți să-l urcați</label>
                <label class="file-upload btn mt-4" >
                    <input type="file" name="file" class="file-input w-100 mb-4" value="" />
                    alege un fișier
                </label>
                <p class="file-name font-weight-bold"></p>
            </div>
            <input type="submit" class="btn btn-action text-uppercase btn-upload-file" style="display: none;" value="Urcă fișier"/>
            @if (session()->has('error'))
                <div class="alert alert-danger p-2 mt-4 mb-0">{{ session()->get('error') }}</div>
            @endif
            @if (session()->has('message'))
                <div class="alert alert-success p-2 mt-4 mb-0">{{ session()->get('message') }}</div>
            @endif
            <!-- <div class="table-info mr-auto mb-0 mt-2" style="display: none;"></div> -->
        </form>
    </div>
    <script defer>
        $(function() {
            const $fileInput = $('.file-input');
            const $fileName = $('.file-name');
            $fileInput.on('change', function() {
                let $filePath = $fileInput.val();
                const $name = $filePath.split(/(\\|\/)/g).pop();
                $fileName.empty();
                $fileName.append(`Nume fișier: ${$name}`);
                $('.btn-upload-file').show();
            });
        });
    </script>
@endsection

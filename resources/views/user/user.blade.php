@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center flex-column">
        <h1 class="text-uppercase mr-auto">Încarcă fișiere</h1>
        <p class="mr-auto mt-2">Aici poți încărca fișiere pentru a putea fi accesate de către altcineva</p>
        <form
            class="w-50 p-5 shadow-sm border mt-4 file-form"
            action="{{ route("user.upload_file") }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleFormControlFile1">Selectați fișierul pe care doriți să-l urcați</label>
                <label class="file-upload btn mt-4">
                    <input type="file" name="file" class="file-input w-100 mb-4" value="" />
                    alege un fișier
                </label>
                <p class="file-name font-weight-bold"></p>
            </div>
            <input type="submit" class="btn btn-action text-uppercase btn-upload-file" style="display: none;" value="Urcă fișier"/>
            @if (session()->has('error'))
                <div class="alert alert-danger p-2 mt-4">{{ session()->get('error') }}</div>
            @endif
            @if (session()->has('message'))
                <div class="alert alert-success p-2 mt-4">{{ session()->get('message') }}</div>
            @endif
        </form>
        <div class="row w-100">
            <div class="col-md-12">
                @if (count($userFiles) > 0)
                    <table class="table table-responsive-sm table-striped table-bordered table-hover shadow-sm mt-5 w-100 table-users">
                        <thead class="">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nume Fișier</th>
                                <th scope="col">Deținător</th>
                                <th scope="col">Tip</th>
                                <th scope="col">Dată</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-body-user-files">
                            @foreach ($userFiles as $file)
                                <tr>
                                    <td>{{ ++$loop->index }}</td>
                                    <td>{{ $file->name ?? '' }}</td>
                                    <td>{{ $user->name ?? '' }}</td>
                                    <td>{{ $file->type ?? '' }}</td>
                                    <td>{{ $file->created_at ?? ''}}</td>
                                    <td class="d-flex align-item-center justify-content-center">
                                        <button
                                            data-file-id="{{ $file->id }}"
                                            class="text-uppercase btn btn-sm btn-danger delete-user-file"
                                            href="{{ route('user.delete_user_file', ['userFileId' => $file->id]) }}"
                                            title="Șterge"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                    </button>|<a
                                            class="text-uppercase btn btn-success btn-sm download-user-file"
                                            target="_blank"
                                            href="{{ route('user.download_user_file', ['userFileId' => $file->id]) }}"
                                            title="Descarcă"
                                        >
                                            <i class="fas fa-download"></i>
                                        </a>
                                        |<a
                                            title="Vizualizare"
                                            class="btn btn-sm btn-primary"
                                            target="_blank"
                                            href="{{ route('user.view_user_file', ['userFileId' => $file->id]) }}"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                <p class="alert alert-success p-2 mt-5 mr-auto">Momentan, nu aveți niciun fișier îcărcat</p>
                @endif
            </div>
        </div>
    </div>
    <script defer>
        $(function() {
            $('.table-users tbody').on('click', '.delete-user-file', handleDeleteUser);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function handleDeleteUser() {
                const $userFileId = $(this).attr('data-file-id');
                if (confirm('Sunteți sigur că doriți să ștergeți acest cont?')) {
                    $.ajax({
                        url: "{{ route('user.delete_user_file') }}",
                        dataType: "json",
                        method: "delete",
                        data: {
                            userFileId: $userFileId
                        }
                    }).done((response, status, jqXhr) => {
                        const { success } = response;
                        location.reload();
                    }).fail((jqXhr, status, error) => {
                        console.error(`error: ${status}, ${error}`);
                    });
                }
            }

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

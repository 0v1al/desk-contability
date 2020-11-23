@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center flex-column">
    <h3 class="mr-auto text-uppercase">Fișierele clientului: {{ $user->name ?? ""}}</h3>
    <div class="row w-100">
        <div class="col-md-12 col-sm-12 col-lg-12">
            @if (session()->has('message'))
                <div class="alert alert-success mt-2">{{ session()->get('message') }}</div>
            @endif
            @if (count($userFiles) > 0)
                <table class="table table-users table-responsive-sm table-striped table-bordered table-hover shadow-sm mt-5 w-100 table-user-files">
                    <div class="table-info" style="display:none;"></div>
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
                                        class="text-uppercase btn btn-sm btn-danger delete-user-file"
                                        data-file-id="{{ $file->id }}"
                                        data-user-id="{{ $file->user_id }}"
                                        title="Șterge"
                                    >
                                        <i class="fas fa-trash-alt"></i>
                                </button>|<a
                                        class="text-uppercase btn btn-success btn-sm download-user-file"
                                        target="_blank"
                                        href="{{ route('admin.download_user_file', ['userFileId' => $file->id, 'userId' => $file->user_id]) }}"
                                        title="Descarcă"
                                    >
                                        <i class="fas fa-download"></i>
                                    </a>
                                    |<a
                                        title="Vizualizare"
                                        class="btn btn-sm btn-primary"
                                        target="_blank"
                                        href="{{ route('admin.view_user_file', ['userFileId' => $file->id, 'userId' => $file->user_id]) }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
            <p class="alert alert-success p-2 mt-5 mr-auto">Momentan, clientul nu a încărcat niciun fișier</p>
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
            const $userId = $(this).attr('data-user-id');
            if (confirm('Sunteți sigur că doriți să ștergeți acest cont?')) {
                $.ajax({
                    url: "{{ route('admin.delete_user_file') }}",
                    dataType: "json",
                    method: "delete",
                    data: {
                        userFileId: $userFileId,
                        userId: $userId
                    }
                }).done((response, status, jqXhr) => {
                    const { success } = response;
                    location.reload();
                }).fail((jqXhr, status, error) => {
                    console.error(`error: ${status}, ${error}`);
                });
            }
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center flex-column">
        <h3 class="mr-auto text-uppercase">Clienți Înregistrați</h3>
        <div class="row w-100">
            <div class="col-md-12 col-sm-12 col-lg-12">
                @if (session()->has('message'))
                    <div class="alert alert-success mt-2 p-2">{{ session()->get('message') }}</div>
                @elseif (session()->has('error'))
                    <div class="alert alert-danger mt-2 p-2">{{ session()->get('error') }}</div>
                @endif
                @if (count($users) > 0)
                    <table class="table table-responsive-sm table-striped table-bordered table-hover shadow-sm mt-5 w-100 table-users">
                        <thead class="">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nume</th>
                                <th scope="col">Email</th>
                                <th scope="col">Admin</th>
                                <th scope="col">Dată Înregistrare</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody class="table-body-user-files">
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ ++$loop->index }}</td>
                                    <td>{{ $user->name ?? '' }}</td>
                                    <td>{{ $user->email ?? '' }}</td>
                                    <td>{{ $user->is_admin == 1 ? 'DA' : 'NU'}}</td>
                                    <td>{{ $user->created_at ?? ''}}</td>
                                    <td>
                                        <a href="{{ route('admin.user_files', ['id' => $user->id]) }}" class="text-uppercase btn btn-sm btn-success">
                                            Fișiere Urcate
                                        </a>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('admin.edit_user', ['userId' => $user->id]) }}"
                                            class="text-uppercase btn btn-sm btn-primary">
                                            Editare
                                        </a>
                                    </td>
                                    <td>
                                        <button
                                            class="text-uppercase btn btn-sm btn-danger delete-user-account"
                                            data-user-id="{{ $user->id }}"
                                        >
                                            Șterge Cont
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                <p class="alert alert-success p-2 mt-5 mr-auto">Momentan, nu aveți niciun client înregistrat</p>
                @endif
            </div>
        </div>
    </div>
    <script defer>
        $(function() {
            $('.table-users tbody').on('click', '.delete-user-account', handleDeleteUser);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function handleDeleteUser() {
                const $userId = $(this).attr('data-user-id');
                if (confirm('Sunteți sigur că doriți să ștergeți acest cont?')) {
                    $.ajax({
                        url: "{{ route('admin.delete_user_account') }}",
                        dataType: "json",
                        method: "delete",
                        data: {
                            userId: $userId
                        }
                    }).done((response, status, jqXhr) => {
                        location.reload();
                    }).fail((jqXhr, status, error) => {
                        console.error(`error: ${status}, ${error}`);
                    });
                }
            }
        });
    </script>
@endsection

@extends('layouts.app')
@section('title','Roles')
@section('content')
    <div class="mb-3">
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
        @can('role_create')
        <a href="{{ route('role.create') }}"><button class="btn btn-theme btn-sm float-end"><i class="fas fa-plus-circle"></i> Create Role</button></a>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered Datatable" width="100%">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Permission</th>
                    <th class="text-center">Action</th>
                    <th class="text-center hidden">Updated at</th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(document).ready(function(){
            var table = $('.Datatable').DataTable({
                ajax: '/role/datatable/ssd',
                columns: [
                    { data: 'plus', name: 'plus', class:'text-center' },
                    { data: 'name', name: 'name' , class:'text-center'},
                    { data: 'permissions', name: 'permissions' , class:'text-center'},
                    { data: 'action', name: 'action', class:'text-center' },
                    { data: 'updated_at', name: 'updated_at', class:'text-center' },
                ],

                "order": [[ 4, "desc" ]],
            });

            $(document).on('click','.delete-btn',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                    text: "Are you sure to want to delete this role...?",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: "DELETE",
                            url: `role/${id}`,
                        })
                        .done(function( res ) {
                            table.ajax.reload();
                        });
                    } else {
                        swal("Your role data is safe!");
                    }
                });
            })
        });
    </script>

@endsection

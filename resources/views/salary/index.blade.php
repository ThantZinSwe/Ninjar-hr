@extends('layouts.app')
@section('title','Salaries')
@section('content')
    <div class="mb-3">
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
        @can('salary_create')
        <a href="{{ route('salary.create') }}"><button class="btn btn-theme btn-sm float-end"><i class="fas fa-plus-circle"></i> Create Salary</button></a>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered Datatable" width="100%">
                <thead>
                    <th class="text-center no-sort no-search"></th>
                    <th class="text-center">Employee</th>
                    <th class="text-center">Month</th>
                    <th class="text-center">Year</th>
                    <th class="text-center">Amount(MMK)</th>
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
                ajax: '/salary/datatable/ssd',
                columns: [
                    { data: 'plus', name: 'plus', class:'text-center' },
                    { data: 'employee_name', name: 'employee_name' , class:'text-center'},
                    { data: 'month', name: 'month' , class:'text-center'},
                    { data: 'year', name: 'year' , class:'text-center'},
                    { data: 'amount', name: 'amount' , class:'text-center'},
                    { data: 'action', name: 'action', class:'text-center' },
                    { data: 'updated_at', name: 'updated_at', class:'text-center' },
                ],

                "order": [[ 6, "desc" ]],
            });

            $(document).on('click','.delete-btn',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                    text: "Are you sure to want to delete this salary...?",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: "DELETE",
                            url: `salary/${id}`,
                        })
                        .done(function( res ) {
                            table.ajax.reload();
                        });
                    } else {
                        swal("Your salary data is safe!");
                    }
                });
            })
        });
    </script>

@endsection

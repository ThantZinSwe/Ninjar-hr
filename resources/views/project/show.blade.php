@extends('layouts.app')
@section('title','Project Details')
@section('content')
@section('extra_css')
    <style>
        .task-item{
            background-color: #fff;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .add_task_btn{
            display: block;
            align-items: center;
            color:black;
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .select2-container{
            z-index: 9999 !important;
        }
        .task-action a {
            width: 26px;
            height: 26px;
            display: inline-block;
            background: #eee;
            text-align: center;
            line-height: 24px;
            font-size: 12px;
            border-radius: 5px;
            margin-bottom: 5px;
            margin-left: 3px;
        }
        .ghost{
            background: #eee;
            border: 2px dashed #333;
        }
    </style>
@endsection

    <div class="mb-3">
        <a href="#"><button class="btn btn-theme btn-sm" id="back-btn"><i class="fas fa-arrow-alt-circle-left"></i> Back</button></a>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $project->title }}</h5>

                    <p class="mb-0">Start Date : <span class="text-muted">{{ $project->start_date }}</span> , Deadline : <span class="text-muted">{{ $project->deadline }}</span></p>

                    <p class="mb-0">Priority :
                    @if ($project->priority == 'low')
                    <span class="badge badge-danger badge-pill">Low</span>
                    @elseif($project->priority == 'middle')
                    <span class="badge badge-warning badge-pill">Middle</span>
                    @else
                    <span class="badge badge-success badge-pill">High</span>
                    @endif

                    , Status :
                    @if ($project->status == 'pending')
                    <span class="badge badge-info badge-pill">Pending</span>
                    @elseif($project->status == 'in_progress')
                    <span class="badge badge-warning badge-pill">In Progress</span>
                    @else
                    <span class="badge badge-success badge-pill">Complete</span>
                    @endif
                    </p>

                    <h5 class="mt-2">Description</h5>
                    <p>{{ $project->description }}</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5>Images</h5>
                    <div id="images">
                        @foreach ($project->images as $image)
                        <img src="{{ asset('storage/project/image/'.$image) }}" alt="" class="img-thumbnail">
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5>Files</h5>
                    @foreach ($project->files as $file)
                    <a href="{{ asset('storage/project/file/'.$file) }}" class="pdf-thumbnail text-decoration-none text-dark" target="_blank"><i class="fas fa-file-pdf"></i><p>File {{ $loop->iteration }}</p></a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Leaders</h5>
                    <div id="leaders">
                        @foreach (($project->leaders ?? []) as $image)
                        <p class="text-muted"><img src= {{ $image->profile_img_path() }} alt="" class = "img-thumbnail2 mx-1">  {{ $image->name }} </p>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Members</h5>
                    <div id="members">
                        @foreach (($project->members ?? []) as $image)
                        <p class="text-muted"><img src= {{ $image->profile_img_path() }} alt="" class = "img-thumbnail2 mx-1">  {{ $image->name }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5>Tasks</h5>
    <div class="task_data"></div>
@endsection
@section('script')

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <script>
        $(document).ready(function(){

            var project_id = "{{ $project->id }}";
            var leaders = @json($project->leaders);
            var members = @json($project->members);

            function sortable(){
                var pendingTaskBoard = document.getElementById('pendingTaskBoard');
                var progressTaskBoard = document.getElementById('progressTaskBoard');
                var completeTaskBoard = document.getElementById('completeTaskBoard');

                Sortable.create(pendingTaskBoard,{
                    draggable: ".task-item",
                    group: "taskBoard",
                    ghostClass: "ghost",
                    animation: 200,
                    store: {
                        set : function(sort){
                            var order = sort.toArray();
                            localStorage.setItem('pendingTaskBoard',order.join(','));
                        }
                    },
                    onSort: function (evt) {
                        setTimeout(function(){
                            var pendingTaskBoard = localStorage.getItem('pendingTaskBoard');
                            console.log(pendingTaskBoard);

                            $.ajax({
                                url: `/task-draggable?project_id=${project_id}&pendingTaskBoard=${pendingTaskBoard}`,
                                type: 'GET',
                                success: function(res){
                                    console.log(res);
                                }
                            });
                        },1000);
	                },
                });

                Sortable.create(progressTaskBoard,{
                   draggable: ".task-item",
                    group: "taskBoard",
                    ghostClass: "ghost",
                    animation: 200,
                    store: {
                        set: function(sort){
                            var order = sort.toArray();
                            localStorage.setItem('progressTaskBoard',order.join(','));
                        }
                    },
                    onSort: function (evt) {
                        setTimeout(function(){
                            var progressTaskBoard = localStorage.getItem('progressTaskBoard');
                            console.log(progressTaskBoard);

                            $.ajax({
                                url: `/task-draggable?project_id=${project_id}&progressTaskBoard=${progressTaskBoard}`,
                                type: 'GET',
                                success: function(res){
                                    console.log(res);
                                }
                            });
                        },1000);
	                },
                });

                Sortable.create(completeTaskBoard,{
                   draggable: ".task-item",
                    group: "taskBoard",
                    ghostClass: "ghost",
                    animation: 200,
                    store: {
                        set: function(sort){
                            var order = sort.toArray();
                            localStorage.setItem('completeTaskBoard',order.join(','));
                        }
                    },
                    onSort: function (evt) {
                        setTimeout(function(){
                            var completeTaskBoard = localStorage.getItem('completeTaskBoard');
                            console.log(completeTaskBoard);

                            $.ajax({
                                url: `/task-draggable?project_id=${project_id}&completeTaskBoard=${completeTaskBoard}`,
                                type: 'GET',
                                success: function(res){
                                    console.log(res);
                                }
                            });
                        },1000);
	                },
                });
            }

            function taskData(){
                $.ajax({
                    url: `/task-data?project_id=${project_id}`,
                    type: 'GET',
                    success: function(res){
                        $('.task_data').html(res);
                        sortable();
                    }
                });
            }

            taskData();

            const gallery = new Viewer(document.getElementById('images'));
            const gallery1 = new Viewer(document.getElementById('leaders'));
            const gallery2 = new Viewer(document.getElementById('members'));

            $(document).on('click','.add_pending_task_btn' , function(event){
                event.preventDefault();

                var member_options = '';

                leaders.forEach(function(leader){
                    member_options += `<option value="${leader.id}">${leader.name}</option>`
                })
                members.forEach(function(member){
                    member_options += `<option value="${member.id}">${member.name}</option>`
                })

                Swal.fire({
                    title: 'Add Pending Task',
                    html:`<form id="add_pending_task">
                        <input type="hidden" name="status" value="pending"/>
                        <input type="hidden" name="project_id" value="${project_id}"/>
                        <div class="md-form">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control"  value="{{ old('title') }}">
                        </div>
                        <div class="md-form">
                            <label for="">Description</label>
                            <textarea class="md-textarea form-control" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="md-form">
                            <label for="">Start Date</label>
                            <input type="text" name="startDate" class="form-control datePicker"  value="{{ old('startDate') }}">
                        </div>
                        <div class="md-form">
                            <label for="">Deadline</label>
                            <input type="text" name="deadline" class="form-control datePicker" value="{{ old('deadline') }}">
                        </div>
                        <div class="form-group text-left">
                            <label for="">Members</label>
                            <select name="members[]" id="" class="form-control select-ninja" multiple>
                                <option value="">Please Choose...</option>
                                ${member_options}
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label for="">Priority</label>
                            <select name="priority" id="" class="form-control select-ninja">
                                <option value="">Choose an Option...</option>
                                <option value="high" {{ old('priority')=='high'? 'selected':'' }}>High</option>
                                <option value="middle" {{ old('priority')=='middle'? 'selected':'' }}>Middle</option>
                                <option value="low" {{ old('priority')=='low'? 'selected':'' }}>Low</option>
                            </select>
                        </div>
                    </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $("#add_pending_task").serialize();
                        console.log(form_data);

                        $.ajax({
                            url: '/task',
                            type: 'POST',
                            data: form_data,
                            success: function(res){
                                taskData();
                            }
                        });
                    }
                })

                $('.select-ninja').select2({
                    placeholder: "Please Choose...",
                    allowClear: true
                });

                $('.datePicker').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale":{
                        "format" : "YYYY-MM-DD",
                    }
                });
            });

            $(document).on('click','.add_progress_task_btn' , function(event){
                event.preventDefault();

                var member_options = '';

                leaders.forEach(function(leader){
                    member_options += `<option value="${leader.id}">${leader.name}</option>`
                })
                members.forEach(function(member){
                    member_options += `<option value="${member.id}">${member.name}</option>`
                })

                Swal.fire({
                    title: 'Add In Progress Task',
                    html:`<form id="add_in_progress_task">
                        <input type="hidden" name="status" value="in_progress"/>
                        <input type="hidden" name="project_id" value="${project_id}"/>
                        <div class="md-form">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control"  value="{{ old('title') }}">
                        </div>
                        <div class="md-form">
                            <label for="">Description</label>
                            <textarea class="md-textarea form-control" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="md-form">
                            <label for="">Start Date</label>
                            <input type="text" name="startDate" class="form-control datePicker"  value="{{ old('startDate') }}">
                        </div>
                        <div class="md-form">
                            <label for="">Deadline</label>
                            <input type="text" name="deadline" class="form-control datePicker" value="{{ old('deadline') }}">
                        </div>
                        <div class="form-group text-left">
                            <label for="">Members</label>
                            <select name="members[]" id="" class="form-control select-ninja" multiple>
                                <option value="">Please Choose...</option>
                                ${member_options}
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label for="">Priority</label>
                            <select name="priority" id="" class="form-control select-ninja">
                                <option value="">Choose an Option...</option>
                                <option value="high" {{ old('priority')=='high'? 'selected':'' }}>High</option>
                                <option value="middle" {{ old('priority')=='middle'? 'selected':'' }}>Middle</option>
                                <option value="low" {{ old('priority')=='low'? 'selected':'' }}>Low</option>
                            </select>
                        </div>
                    </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $("#add_in_progress_task").serialize();
                        console.log(form_data);

                        $.ajax({
                            url: '/task',
                            type: 'POST',
                            data: form_data,
                            success: function(res){
                                taskData();
                            }
                        });
                    }
                })

                $('.select-ninja').select2({
                    placeholder: "Please Choose...",
                    allowClear: true
                });

                $('.datePicker').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale":{
                        "format" : "YYYY-MM-DD",
                    }
                });
            });

            $(document).on('click','.add_complete_task_btn' , function(event){
                event.preventDefault();

                var member_options = '';

                leaders.forEach(function(leader){
                    member_options += `<option value="${leader.id}">${leader.name}</option>`
                })
                members.forEach(function(member){
                    member_options += `<option value="${member.id}">${member.name}</option>`
                })

                Swal.fire({
                    title: 'Add Complete Task',
                    html:`<form id="add_complete_task">
                        <input type="hidden" name="status" value="complete"/>
                        <input type="hidden" name="project_id" value="${project_id}"/>
                        <div class="md-form">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control"  value="{{ old('title') }}">
                        </div>
                        <div class="md-form">
                            <label for="">Description</label>
                            <textarea class="md-textarea form-control" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <div class="md-form">
                            <label for="">Start Date</label>
                            <input type="text" name="startDate" class="form-control datePicker"  value="{{ old('startDate') }}">
                        </div>
                        <div class="md-form">
                            <label for="">Deadline</label>
                            <input type="text" name="deadline" class="form-control datePicker" value="{{ old('deadline') }}">
                        </div>
                        <div class="form-group text-left">
                            <label for="">Members</label>
                            <select name="members[]" id="" class="form-control select-ninja" multiple>
                                <option value="">Please Choose...</option>
                                ${member_options}
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label for="">Priority</label>
                            <select name="priority" id="" class="form-control select-ninja">
                                <option value="">Choose an Option...</option>
                                <option value="high" {{ old('priority')=='high'? 'selected':'' }}>High</option>
                                <option value="middle" {{ old('priority')=='middle'? 'selected':'' }}>Middle</option>
                                <option value="low" {{ old('priority')=='low'? 'selected':'' }}>Low</option>
                            </select>
                        </div>
                    </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $("#add_complete_task").serialize();
                        console.log(form_data);

                        $.ajax({
                            url: '/task',
                            type: 'POST',
                            data: form_data,
                            success: function(res){
                                taskData();
                            }
                        });
                    }
                })

                $('.select-ninja').select2({
                    placeholder: "Please Choose...",
                    allowClear: true
                });

                $('.datePicker').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale":{
                        "format" : "YYYY-MM-DD",
                    }
                });
            });

            $(document).on('click','.task_edit_btn' , function(event){
                event.preventDefault();

                var task = JSON.parse(atob($(this).data('task')));
                var task_members = JSON.parse(atob($(this).data('task-members')));

                var member_options = '';

                leaders.forEach(function(leader){
                    member_options += `<option value="${leader.id}" ${(task_members.includes(leader.id) ? 'selected':'')}>${leader.name}</option>`
                })
                members.forEach(function(member){
                    member_options += `<option value="${member.id}" ${(task_members.includes(member.id) ? 'selected':'')}>${member.name}</option>`
                })

                Swal.fire({
                    title: 'Edit Task',
                    html:`<form id="edit_task">
                        <input type="hidden" name="project_id" value="${project_id}"/>
                        <div class="md-form">
                            <label for="" class="active">Title</label>
                            <input type="text" name="title" class="form-control" value="${task.title}"/>
                        </div>
                        <div class="md-form">
                            <label for="" class="active">Description</label>
                            <textarea class="md-textarea form-control" name="description" rows="3">${task.description}</textarea>
                        </div>
                        <div class="md-form">
                            <label for="" class="active">Start Date</label>
                            <input type="text" name="startDate" class="form-control datePicker"  value="${task.start_date}"/>
                        </div>
                        <div class="md-form">
                            <label for="" class="active">Deadline</label>
                            <input type="text" name="deadline" class="form-control datePicker" value="${task.deadline}"/>
                        </div>
                        <div class="form-group text-left">
                            <label for="">Members</label>
                            <select name="members[]" id="" class="form-control select-ninja" multiple>
                                <option value="">Please Choose...</option>
                                ${member_options}
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label for="">Priority</label>
                            <select name="priority" id="" class="form-control select-ninja">
                                <option value="">Choose an Option...</option>
                                <option value="high" ${task.priority == 'high' ? 'selected' : ''}>High</option>
                                <option value="middle" ${task.priority == 'middle' ? 'selected' : ''}>Middle</option>
                                <option value="low" ${task.priority == 'low' ? 'selected' : ''}>Low</option>
                            </select>
                        </div>
                    </form>`,
                    showCancelButton: false,
                    confirmButtonText: 'Confirm',
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form_data = $("#edit_task").serialize();
                        console.log(form_data);

                        $.ajax({
                            url: `/task/${task.id}`,
                            type: 'PUT',
                            data: form_data,
                            success: function(res){
                                taskData();
                            }
                        });
                    }
                })

                $('.select-ninja').select2({
                    placeholder: "Please Choose...",
                    allowClear: true
                });

                $('.datePicker').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale":{
                        "format" : "YYYY-MM-DD",
                    }
                });
            });

            $(document).on('click','.task_delete_btn',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                    text: "Are you sure to want to delete this task...?",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: "DELETE",
                            url: `/task/${id}`,
                        })
                        .done(function( res ) {
                            taskData();
                        });
                    } else {
                        swal("Your employee data is safe!");
                    }
                });
            })
        });
    </script>

@endsection

@extends('layout')

@section('css')
	<link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">
    <link type="text/css"  href="{{ asset('plugins/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detailProject.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checklist.css') }}">
    <link rel="stylesheet" href="{{ asset('css/popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/colorPick.css') }}">
    <style>
        .img-customize{
            height: 23px !important;
            width: 23px !important;
        }
        .item-info-customize{
            margin-bottom: 2px !important;
        }
        /* Color Picker */
        .picker {
            border-radius: 5px;
            width: 36px;
            height: 36px;
            cursor: pointer;
            -webkit-transition: all linear .2s;
            -moz-transition: all linear .2s;
            -ms-transition: all linear .2s;
            -o-transition: all linear .2s;
            transition: all linear .2s;
            border: thin solid #eee;
        }
        .picker:hover {
            transform: scale(1.1);
        }
        /*.p-customize{
            margin-bottom: 5px !important;
        }*/
    </style>
    <!--Footable
    <link href="../plugins/footable/css/footable.core.css" rel="stylesheet">
    <link href="../plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />-->
    
@stop

@section('container')
                  <div class="col-lg-4">
                        <div class="card-box">
                            <!--data-toggle="modal" 
                            data-target="#modalAdd"-->
                            <a href="#" class="pull-right btn btn-default btn-sm waves-effect waves-light btn-add-task">
                                <i class="fa fa-plus"></i>
                            </a>
                            <h4 class="text-dark header-title m-t-0">Upcoming</h4>
                            <br>

                            <ul class="sortable-list taskList list-unstyled" id="upcoming">
                                
                                <!--TASKS-->
                                @if(count($tupcoming))
                                    @foreach($tupcoming as $task)
                                        <li class="task-detail task-main">
                                            <div class="pull-right">
                                                <a onclick="remove({{ $task->id }},this);">
                                                    <i class="fa fa-trash" style="color: red;
                                                cursor: pointer;"></i>
                                                </a>
                                                <label></label>
                                            </div>
                                    <!--<div class="card-tag">
                                        <span class="card-label card-label-red" title="Test">Test</span>
                                        <span class="card-label card-label-blue" title="Diseño">Diseño</span>
                                        <span class="card-label card-label-yellow" title="Mejoras de funcionalidades">Mejoras de funcionalidades</span>
                                    </div>-->
                                        <a class="task-title btn-task-detail" id="task{{ $task->id }}"
                                           data-toggle="modal" data-target="#modalDetail"
                                        data-url="{{ route('app.details.task', $task->id ) }}">
                                            {{ $task->name }}
                                        </a>
                                        <div class="m-t-20" style="margin-top: 7px !important;">
                                            <p class="pull-right m-b-0">
                                                <i class="fa fa-comment-o"></i> 
                                                    <span id="comment{{ $task->id }}">
                                                        {{ $task->task_activities()->where('type','message')->count() }}
                                                    </span>
                                            </p>
                                            <p class="pull-right m-b-0" style="margin:0px 25px;">
                                                <i class="fa fa-paperclip"></i>
                                                <span id="attachment{{ $task->id }}">
                                                   {{ $task->task_attachments()->count() }}
                                                </span>
                                            </p>
                                            <p class="pull-right m-b-0" style="margin:0px 25px;">
                                                <?php
                                                    $result = 0;
                                                    if($task->task_subtasks()->count() != 0){
                                                        $result =  ($task->task_subtasks()->where('isComplete',1)->count() /
                                                                $task->task_subtasks()->count()) * 100;
                                                    }
                                                ?>
                                                <span id="percent{{ $task->id }}">
                                                   {{ round($result) }}
                                                </span>
                                                <i class="fa fa-percent"></i>

                                            </p>
                                            <p class="m-b-0">
                                                <a href="#" class="text-muted">
                                                    <img src="/img/default-user.png" alt="task-user"
                                                         class="thumb-sm rounded-circle m-r-10 img-task">
                                                </a>
                                            </p>
                                        </div>
                                        </li>
                                    @endforeach
                                @endif
                               <!--END TASKS-->
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">
                            <h4 class="text-dark header-title m-t-0">In Progress</h4>
                            <br>

                            <ul class="sortable-list taskList list-unstyled" id="inprogress">
                                <!--TASKS-->
                                @if(count($tprogress))
                                    @foreach($tprogress as $task)
                                        <li class="task-detail task-main">
                                            <div class="pull-right">
                                                <a onclick="remove({{ $task->id }},this);">
                                                    <i class="fa fa-trash" style="color: red;
                                                cursor: pointer;"></i>
                                                </a>
                                                <label></label>
                                            </div>
                                            <a class="task-title btn-task-detail" id="task{{ $task->id }}"
                                               data-toggle="modal" data-target="#modalDetail"
                                               data-url="{{ route('app.details.task', $task->id ) }}">
                                                {{ $task->name }}
                                            </a>
                                            <div class="m-t-20" style="margin-top: 7px !important;">
                                                <p class="pull-right m-b-0">
                                                    <i class="fa fa-comment-o"></i>
                                                    <span id="comment{{ $task->id }}">
                                                        {{ $task->task_activities()->where('type','message')->count() }}
                                                    </span>
                                                </p>
                                                <p class="pull-right m-b-0" style="margin:0px 25px;">
                                                    <i class="fa fa-paperclip"></i>
                                                    <span id="attachment{{ $task->id }}">
                                                   {{ $task->task_attachments()->count() }}
                                                </span>
                                                </p>
                                                <p class="pull-right m-b-0" style="margin:0px 25px;">
                                                    <?php
                                                    $result = 0;
                                                    if($task->task_subtasks()->count() != 0){
                                                        $result =  ($task->task_subtasks()->where('isComplete',1)->count() /
                                                                $task->task_subtasks()->count()) * 100;
                                                    }
                                                    ?>
                                                    <span id="percent{{ $task->id }}">{{ round($result) }}</span>
                                                    <i class="fa fa-percent"></i>

                                                </p>
                                                <p class="m-b-0">
                                                    <a href="#" class="text-muted">
                                                        <img src="/img/default-user.png" alt="task-user"
                                                             class="thumb-sm rounded-circle m-r-10 img-task">
                                                    </a>
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                                <!--END TASKS-->
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-box">
                            <h4 class="text-dark header-title m-t-0">Completed</h4>
                           <br>

                            <ul class="sortable-list taskList list-unstyled" id="completed">
                                <!--TASKS-->
                                @if(count($tcompleted))
                                    @foreach($tcompleted as $task)
                                        <li class="task-detail task-main">
                                            <div class="pull-right">
                                               <a onclick="remove({{ $task->id }},this);">
                                                    <i class="fa fa-trash" style="color: red;
                                                    cursor: pointer;"></i>
                                                </a>
                                                <label></label>
                                            </div>
                                            <a class="task-title btn-task-detail" id="task{{ $task->id }}"
                                               data-toggle="modal" data-target="#modalDetail"
                                               data-url="{{ route('app.details.task', $task->id ) }}">
                                                {{ $task->name }}
                                            </a>
                                            <div class="m-t-20" style="margin-top: 7px !important;">
                                                <p class="pull-right m-b-0">
                                                    <i class="fa fa-comment-o"></i>
                                                    <span id="comment{{ $task->id }}">
                                                        {{ $task->task_activities()->where('type','message')->count() }}
                                                    </span>
                                                </p>
                                                <p class="pull-right m-b-0" style="margin:0px 25px;">
                                                    <i class="fa fa-paperclip"></i>
                                                    <span id="attachment{{ $task->id }}">
                                                   {{ $task->task_attachments()->count() }}
                                                </span>
                                                </p>
                                                <p class="pull-right m-b-0" style="margin:0px 25px;">
                                                    <?php
                                                    $result = 0;
                                                    if($task->task_subtasks()->count() != 0){
                                                        $result =  ($task->task_subtasks()->where('isComplete',1)->count() /
                                                                $task->task_subtasks()->count()) * 100;
                                                    }
                                                    ?>
                                                    <span id="percent{{ $task->id }}">
                                                   {{ round($result) }}
                                                </span>
                                                    <i class="fa fa-percent"></i>

                                                </p>
                                                <p class="m-b-0">
                                                    <a href="#" class="text-muted">
                                                        <img src="/img/default-user.png" alt="task-user"
                                                             class="thumb-sm rounded-circle m-r-10 img-task">
                                                    </a>
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                                <!--END TASKS-->
                            </ul>
                        </div>
                    </div>
    <div id="modalDetail" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('partials.detail-task')
            </div>
        </div>
    </div>
@stop

@section('scripts')

	<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/x-editable/js/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('js/assets/task.js') }}"></script>
    <script src="{{ asset('js/dropzone.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/assets/subtask.js') }}"></script>
    <script src="{{ asset('js/checklist.js') }}"></script>
    <script src="{{ asset('js/assets/attachment.js') }}"></script>
    <script src="{{ asset('js/assets/activity.js') }}"></script>
    <script src="{{ asset('js/colorPick.js') }}"></script>
     <!--FooTable
     <script src="../plugins/footable/js/footable.all.min.js"></script>

     <script src="../plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>-->

     <!--FooTable Example
     <script src="assets/pages/jquery.footable.js"></script>-->

	<script>

		$("#upcoming, #inprogress, #completed").sortable({
                    connectWith: ".taskList",
                    placeholder: 'task-placeholder',
                    forcePlaceholderSize: true,
                    update: function (event, ui) {
                        var todo = $("#todo").sortable("toArray");
                        var inprogress = $("#inprogress").sortable("toArray");
                        var completed = $("#completed").sortable("toArray");
                    }
        }).disableSelection();

        newTask();

        //Dropzone configuration
        Dropzone.autoDiscover = false;
        $("#mydropzone").dropzone({
            //paramName: "file",
            url: "/attachment/upload",
            addRemoveLinks : true,
            //acceptedFiles: 'image/*',
            maxFilesize: 5.5,
            maxFiles:1,
            dictResponseError: 'Error al subir foto!',
            success:function(file,data){
                if(data.status == "Uploaded")
                {
                    notification('Attachment','File Uploaded Successfully', 'success',3000);
                    getAll();
                    concatActivity(data.activity.date_time, data.photo, data.username,data.user,
                        data.activity.message);
                    $("#attachment"+data.taskid).text(data.count);
                }
              else notification('Error','An error occurred, try again','error',3000);

              Dropzone.forElement("#mydropzone").removeAllFiles(true);
            }
        });
        
        //editable
        $('#titleTask').editable({
            validate: function(value) {
                if($.trim(value) == '') return 'This field is required';
            },
            mode: 'inline',
            inputclass: 'input-sm-overwrite',
            url:'/task/update',
            success:function(response, newValue){
                if(response.status == "Updated"){
                    concatActivity(response.activity.date_time, response.photo, response.username,
                        response.user, response.activity.message);
                    $("#task"+response.taskid).text(response.taskname);
                }

            }
        });

        $('#descriptionTask').editable({
            showbuttons: 'bottom',
            mode: 'inline',
            inputclass: 'input-large-overwrite',
            url:'/task/update',
            success:function(response, newValue){
                if(response.status == "Updated"){
                    concatActivity(response.activity.date_time, response.photo, response.username,
                        response.user, response.activity.message);
                }
            }
        });

        $('.form_datetime').datetimepicker({
            format: "dd-M - hh:ii",
            autoclose: true,
            todayBtn: true,
            startDate: moment().format('YYYY-MM-D'),
            pickerPosition: "bottom-left"

        }).on('changeDate',function(ev){
            var dateParse = moment(ev.date).format('YYYY-MM-DD HH:mm:ss'); 
            updateDate(dateParse);
        });

        showInformation();
        removeAll();
        saveComment();

        /*function myFunction() {
            var popup = document.getElementById("myPopup");
            popup.classList.toggle("show");
        }*/
        function deselect(e) {
            $('.pop').slideFadeToggle(function() {
                e.removeClass('selected');
            });
        }

        $(function() {
            $('#myPopup').on('click', function() {
                if($(this).hasClass('selected')) {
                    deselect($(this));
                } else {
                    $(this).addClass('selected');
                    $('.pop').slideFadeToggle();
                }
                return false;
            });

            $('.close').on('click', function() {
                deselect($('#contact'));
                return false;
            });
        });

        $.fn.slideFadeToggle = function(easing, callback) {
            return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
        };

        $(".picker").colorPick({
            'initialColor' : '#8e44ad',
            'palette': ["#1abc9c", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1"],
            'onColorSelected': function() {
                alert("The user has selected the color: " + this.color);
                this.element.css({'backgroundColor': this.color, 'color': this.color});
            }
        });

	</script>
@stop



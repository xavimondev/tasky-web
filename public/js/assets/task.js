//modify buttons style
$.fn.editableform.buttons =
        '<button type="submit" class="btn btn-primary editable-submit btn-sm waves-effect waves-light"><i class="md md-check"></i></button>' +
        '<button type="button" class="btn btn-danger editable-cancel btn-sm waves-effect"><i class="md md-close"></i></button>';

const upcoming = document.querySelector('#upcoming');

function newTask() {

    $(".btn-add-task").click(function() {
        var html = '<li class="task-detail task-main">';
        html += '<div class="pull-right">';
        html += '<a href="#" onclick="cancel(this);" class="btn-cancel"><i class="fa fa-times"' +
            'style="color: red;cursor: pointer;"></i></a>';
        html += '</div>';
        html += '<span class="task-title">';
        html += '<input type="text" name="task" id="task-field" class="input-cus" required' +
            'autofocus/>';
        html += '</span>';
        html += '</li>';

        $("#upcoming").append(html);

        $('#task-field').focus();
    });

}

function cancel (container) {
    const first = container.parentElement;
    const second = first.parentElement;
    upcoming.removeChild(second);
}

function saveTask () {
    var html,btnCancel,code;
    $(".main-upcoming").on('keyup',"#task-field",function(e) {
        btnCancel = document.querySelector('.btn-cancel');
        code = e.keyCode ? e.keyCode : e.which;
        html = "";
        if (code == 13) {
            $.post('/task/save',{task:$("#task-field").val()},function(data){
                if(data.status == "Saved"){
                    var nameTask = $("#task-field").val();

                    //Delete current container task "li"
                    const first = btnCancel.parentElement;
                    const second = first.parentElement;
                    upcoming.removeChild(second);

                    //Add task with trash icon
                    html += '<li class="task-detail task-main ui-sortable-handle" id="'+data.id+'">';
                    html += '<div class="pull-right">';
                    html += '<a onclick="remove('+data.id+',this)">';
                    html += '<i class="fa fa-trash" style="color: red;cursor: pointer;"></i>';
                    html += '</a>';
                    html += '</div>';
                    html += '<div class="card-tags ct'+data.id+'">';
                    html += '</div>';
                    html += '<a class="task-title btn-task-detail" id="task'+data.id+'" data-url="/task/detail/'+data.id+'"' +
                        'data-toggle="modal" data-target="#modalDetail">' +nameTask+ '</a>';
                    html += '<div class="m-t-20" style="margin-top: 7px !important;height:20px;">';
                    html += '<p class="pull-right m-b-0">';
                    html += '<i class="fa fa-comment-o"></i> ';
                    html += '<span id="comment'+data.id+'">0</span>';
                    html += '</p>';
                    html += '<p class="pull-right m-b-0" style="margin:0px 28px;">';
                    html += '<i class="fa fa-paperclip"></i> ';
                    html += '<span id="attachment'+data.id+'">0</span>';
                    html += '</p>';
                    html += '<p class="pull-right m-b-0" style="margin:0px 12px;">';
                    html += '<span id="percent'+data.id+'">0</span> ';
                    html += '<i class="fa fa-percent"></i>';
                    html += '</p>';
                    html += '<p class="m-b-0 text-muted mt'+data.id+'">';
                    html += '</p>';
                    html += '</div></li>';

                    $("#upcoming").append(html);
                }
            });
        }
    });
}

function remove(id,cont) {

    if(id > 0 ){
        (new PNotify({
            title: 'Confirmation',
            text: '¿Are you sure?',
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            confirm: {
                confirm: true
            },
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            }
            })).get().on('pnotify.confirm', function(){
                $.get('/task/delete/'+id,function(rpta){
                    if (rpta == "Removed"){
                        notification('Remove Task','Task Removed Successfully','success',3000);
                        
                        //Delete current container task "li"
                        const first = cont.parentElement;
                        const second = first.parentElement;
                        second.parentElement.removeChild(second);
                    }else
                        notification('Error','An error occurred, try again','error',3000);
                });
        });
    }
}

function updateDate(datep) {
    $.post('/task/update',{value:datep,name:"dueDateTask"},function(response){
        if(response.status == "Updated")
        {
            concatActivity(response.activity.date_time, response.photo, response.username,
                response.user, response.activity.message);
        }
    });
}

function updateStatus(value,id)
{
    $.post('/task/update',{id:id,value:value,name:"status"},function(data){
        console.log(data);
    });
}

function showInformation() {
    var description = "";
    var due_date = "";
    var html,status,can,result,url,tbody,date,activity,message,content,
        label,members,isMember,membersTask,owner;
    var table = $("#ListAttachments tbody");

   $('.main-container').on('click','.btn-task-detail',function(){

        url = $(this).data('url');

        html = "";
        can = 0;
        table.html('');
        tbody = "";
        activity = "";
        label = "";
        members = "";
        membersTask = "";
        isMember = "";
        //owner = "";

        $.get(url,function(data){
            $('#titleTask').editable('setValue', data.task.name);
            
            if(data.task.description == null || data.task.description == "")
                description = 'Edit description';
            else description = data.task.description;

            $("#descriptionTask").editable('setValue',description);

            if(data.task.due_date == null || data.task.due_date == "") due_date = "";
            else  due_date = moment(data.task.due_date).format('DD-MMM - HH:mm');

            $("#dueDate").val(due_date);

            $(".remove-this").remove();
            $(".progress-md").html("<div class=\"progress-bar progress-bar-inverse\"\n" +
                "\t\role=\"progressbar\" data-value=\"0\" id=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\"\n" +
                "\taria-valuemax=\"100\" style=\"width:0%;font-size:12.8px;\">0%</div>");

            //Lista el checklist o subtareas de la tarea seleccionada
            if(data.subtasks.length){

                result = 100 / data.subtasks.length;

                for(var i=0; i<data.subtasks.length; i++){
                    if(data.subtasks[i].isComplete) {
                        status = "checked";
                        ++can;
                    }
                    else status = "";

                    html += '<span class="todo-wrap remove-this" style="height: 36px;">';
                    html += '<input type="checkbox" value="'+result+'"' +
                        'onchange="updateSubTask('+ data.subtasks[i].id +',this);" ' +
                        'id="'+ data.subtasks[i].id +'" '+status+'>';
                    html += '<label for="'+ data.subtasks[i].id +'" ' +
                        'class="todo"><i class="fa ' +
                        'fa-check"></i>'+ data.subtasks[i].name +'</label>';
                    html += '<span class="delete-item" ' +
                        'onclick="removeSubTask('+ data.subtasks[i].id +',this)"' +
                        ' title="remove"><i class="fa fa-trash"></i></span>';
                    html += '</span>';

                }
                $("#formCheckList").prepend(html);

                result = (can / data.subtasks.length) * 100;

                $(".progress-md").html('<div class="progress-bar progress-bar-inverse" ' +
                    'role="progressbar" data-value="'+result.toFixed(0)+'" id="progressbar" aria-valuenow="'+ result.toFixed(0) +'aria-valuemin="0" aria-valuemax="100" ' +
                    'style="width:'+result.toFixed(0)+'%;font-size:12.8px;">' +
                    ''+ result.toFixed(0) +'%</div>');
            }
            
            //Lista los archivos adjuntos de la tarea seleccionada
            if(data.attachments.length){
                for (var j = 0; j < data.attachments.length; j++) {
                    date = moment(data.attachments[j].date.date).format('YYYY-MM-DD - HH:mm');
                    tbody += "<tr>";
                    tbody += "<td style='text-align:center;'>"+getPreview(data.attachments[j].ext,data.attachments[j].url)+"</td>";
                    tbody += "<td>";
                    tbody += "<a href='/attachment/download/"+data.attachments[j].id+"'>"+data.attachments[j].name+"</a>";
                    tbody += "</td>";
                    tbody += "<td>"+data.attachments[j].size+"</td>";
                    tbody += "<td>"+date+"</td>";
                    tbody += "<td>";
                    tbody += "<button class='btn btn-danger' onclick='removeAttachment("+data.attachments[j].id+",this)' style='font-size:10px;cursor:pointer;'><i class='fa fa-trash'></i></button>";
                    tbody += "</td>";
                    tbody += "</tr>";
                }
                table.html(tbody);
            }

            //Lista las actividades de la tarea seleccionada
            $(".timeline-2").html("");
            if(data.activities.length){
                for (var i = 0; i < data.activities.length; i++) {
                    date = moment(data.activities[i].date_time).fromNow();

                    activity += '<div class="time-item">';
                    activity += '<div class="item-info item-info-customize">';
                    activity += '<div class="text-muted"><small>'+date+'</small></div>';
                    activity += '<p>';
                    activity += '<img class="align-self-start rounded mr-3 img-fluid img-customize thumb-sm" ' +
                        'src="'+data.activities[i].photouser+'" alt="'+data.activities[i].username+'" />';
                    activity += '<a href="#" ' +
                        'class="text-info">'+data.activities[i].nameuser+'</a> '+data.activities[i].message+'';
                    activity += '</p>';

                    activity += '</div>';
                    activity += '</div>';
                }
                $(".timeline-2").html(activity);

            }

            //Lista las etiquetas de la tarea seleccionada
            $(".card-tags-detail").html('');
            if(data.labels.length){

                $("#listLabels").show();

                for (var i = 0; i < data.labels.length; i++) {
                    label += '<span class="card-label-detail" ' +
                        'style="background-color:'+data.labels[i].color+'" ' +
                        'title="'+data.labels[i].name+'">'+data.labels[i].name+'</span>';
                }
                $(".card-tags-detail").html(label);

            }else{
                $("#listLabels").hide();
            }

            //Lista los miembros del proyecto incluido el propietario en el popup miembros
            if(data.owner.isMember) isMember = "selected";
            else isMember = "deselected";

            owner = '<div class="container-members btn-member '+isMember+'" data-id="'+data.owner.id+'">';
            owner += '<img class="rounded-circle img-position" height="29" width="29" src="'+data.owner.photo+'" />';
            owner += '<span style="float:left;font-size: 15px;vertical-align: middle;"  ' +
                        '>'+data.owner.first_name+ ' ' +data.owner.last_name+'</span>';
            owner += '</div>';

            $("#members").html(owner);
            
            if(data.members.length)
            {
                for (var i = 0; i < data.members.length; i++)
                {
                    if(data.members[i].isMember) isMember = "selected";
                    else isMember = "deselected";
                    
                    members += '<div class="container-members btn-member '+isMember+'" data-id="'+data.members[i].id+'">';
                    members += '<img class="rounded-circle img-position" height="29" width="29" src="'+data.members[i].photo+'" />';
                    members += '<span style="float:left;font-size: 15px;vertical-align: middle;"  ' +
                        '>'+data.members[i].name+ ' ' +data.members[i].last_name+'</span>';
                    members += '</div>';
                }
                $("#members").append(members);
            }

            //Lista los usuarios que son responsables de la tarea
            $("#membersTask").html('');
            if(data.membersTask.length)
            {
                $("#listMembers").show();

                for (var i = 0; i < data.membersTask.length; i++)
                {
                    membersTask += '<img id="memberTD'+data.membersTask[i].id+'" style="border-radius:6px;border:1px solid #4c5667" ' +
                        'alt="'+data.membersTask[i].lastname+'" height="35" ' +
                        'width="35" src="'+data.membersTask[i].photo+'" /> ';
                }

                $("#membersTask").html(membersTask);

            }else{
                $("#listMembers").hide();
            }

        });
   });
}

function getPreview(ext,url){
    var icon;

    if (ext == "xlsx" || ext == "xls" || ext == "xlsm") {
		icon = "<i class='fa fa-file-excel-o fa-3x'></i>";
    }else if (ext == "tiff" || ext == "png" || ext == "jpg" || ext == "jpeg" 
        || ext == "tif" || ext == "bmp" || ext == "gif") {
		icon = "<img width='80px' alt='Image' src='/"+url+"'/>";
	}else if (ext == "doc" || ext == "docx") {
		icon = "<i class='fa fa-file-word-o fa-3x'></i>";
	}else if (ext == "pptx" || ext == "ppt" || ext == "pptm") {
		icon = "<i class='fa fa-file-powerpoint-o fa-3x'></i>";
    }else if (ext == "mp4" || ext == "avi" || ext == "mov" || ext == "wmv" 
        || ext == "mkv" || ext == "3gp") {
		icon = "<i class='fa fa-file-video-o fa-3x'></i>";
	}else if (ext == "pdf") {
		icon = "<i class='fa fa-file-pdf-o fa-3x'></i>";
	}else if (ext == "html" || ext == "css" || ext == "js") {
		icon = "<i class='fa fa-file-code-o fa-3x'></i>";
	}else if (ext == "mp3" || ext == "wma" || ext == "wav") {
		icon = "<i class='fa fa-file-sound-o fa-3x'></i>";
	}else if (ext == "zip" || ext == "rar") {
		icon = "<i class='fa fa-file-zip-o fa-3x'></i>";
	}else if (ext == "txt") {
		icon = "<i class='fa fa-file-text-o fa-3x'></i>";
	}else {
		icon = "<i class='fa fa-file-o fa-3x'></i>";
    }
    return icon;
}

                                

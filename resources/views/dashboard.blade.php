<x-app-layout>
    @include('components.tareas.crear-tarea')
    @include('components.tareas.editar-tarea')
    @hasanyrole('Administrativo|Gerente|Admin')
    <button type="button" class="btn btn-primary mb-3 btn-lg"  data-bs-toggle="modal" data-bs-target="#create-homework">
        Agregar
    </button>
    @endhasanyrole

    <div class="row" style="position: inherit;">
        <div class="col-lg-12">
            <hr class="mt-0">
            <div id='loading'>Cargando...</div>
            <div id='calendar' style="max-height: 720px !important"></div>
        </div>
    </div>
    @push('scripts')

    <script>
        var calendar;
        $(document).ready(function () {
            var SITEURL = "{{ route('homeworks') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
                refetchResourcesOnNavigate: false,
                contentHeight: 600,
                weekNumbers: true,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek'
                },
                locale: 'es',
                displayEventTime: false,
                firstDay: 1,
                editable: true,
                navLinks: true,
                selectable: true,
                eventStartEditable: "{{ auth()->user()->hasRole(['Administrativo','Gerente','Admin']) }}" ? true : false,
                selectConstraint:{
                    start: '00:01',
                    end: '23:59',
                },
                eventConstraint:{
                    startTime: '00:00',
                    endTime: '24:00',
                },
                events: {
                    url: SITEURL,
                    failure: function() {
                    }
                },
                loading: function(bool) {
                    document.getElementById('loading').style.display =
                    bool ? 'block' : 'none';
                },

                //UPDATE AL MOVER REGISTRO
                eventDrop: function(info) {
                    var dates = [];
                    dates.push(info.event.startStr);
                    info.relatedEvents.filter(element => {
                        dates.push(element.startStr)
                    })
                    var id = info.event.id;
                    var min = dates.reduce(function (valor1, valor2) { return new Date(valor1) <  new Date(valor2) ? valor1 : valor2; });
                    var max = dates.reduce(function (valor1, valor2) { return new Date(valor1) > new Date(valor2) ? valor1 : valor2; });
                    var data = {
                        min
                    }
                    var url = "{{route('homework.move', ":id")}}";
                    url = url.replace(":id", id);
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: { min },
                        success: function(data){
                            window.location = "{{ route('dashboard') }}";
                        }
                    });
                },

                //EDITAR REGISTRO
                eventClick: function(arg) {
                    var url = "{{route('homework.edit', ":id")}}";
                    url = url.replace(":id", arg.event.id);
                    $.ajax({
                        url: url,
                        dataType: "json",
                        success: function(response){
                            $('#edit-homework').modal('show');
                            $('#id').val(arg.event.id);
                            $('#edit_date').val(response.homework.date);
                            $('#edit_title').val(response.homework.title);
                            $('#edit_description').val(response.homework.description);
                            $('#edit_comment').val(response.homework.comment);
                            $('#for_admin').val(response.homework.for_admin);
                            if(response.homework.user_id == '{{ auth()->user()->id }}' && response.homework.is_own == 0){
                                $("#eliminar").show();
                                $("#title_modal").text('Ver tarea');
                                $("#edit_date").prop('readonly', true);
                                $("#edit_title").prop('readonly', true);
                                $("#edit_description").prop('readonly', true);
                                $("#edit_priority_id").css("pointer-events", "auto");
                                $("#selected_option").css("display", "none");
                                $("#view").val(1);
                            }else{
                                $("#eliminar").show();
                                $("#title_modal").text('Editar tarea');
                                $("#edit_date").prop('readonly', false);
                                $("#edit_title").prop('readonly', false);
                                $("#edit_description").prop('readonly', false);
                                $("#edit_priority_id").css("pointer-events", "none");
                                $("#selected_option").css("display", "block");
                                $("#view").val(0);
                            }
                            switch(response.homework.for_admin){
                                case 0:
                                    $('#edit_mi').prop('checked', true);
                                    editSelectI();
                                    break;
                                case 1:
                                    $('#edit_gerente').prop('checked', true);
                                    editSelectGerente(response.homework.user_id);
                                    break;
                                case 2:
                                    $('#edit_admin').prop('checked', true);
                                    editSelectAdmin(response.homework.user_id);
                                    break;
                                case 3:
                                    $('#edit_jh').prop('checked', true);
                                    editSelectJefe(response.homework.user_id);
                                    break;
                                case 4:
                                    $('#edit_administrativo').prop('checked', true);
                                    editSelectAdministrativo(response.homework.user_id);
                                    break;
                            }
                            if(response.homework.status == 1){
                                var status = true;
                            }else{
                                var status = false;
                            }
                            $('#status').prop('checked', status);
                            $('#edit_priority_id').val(response.homework.priority_id);
                        }
                    });
                },

                eventDidMount(e){
                    var i = document.createElement('i');
                    if(e.event.extendedProps.icon){
                        i.className = 'fas fa-check-circle fa-lg float-right text-white mr-2 mt-1';
                        e.el.prepend(i);
                    }
                },

            });
            calendar.render();
        });

        function hideElements()
        {
            $("#title_modal").text('Ver tarea');
            $("#edit_date").prop('readonly', true);
            $("#edit_title").prop('readonly', true);
            $("#edit_description").prop('readonly', true);
            $("#edit_priority_id").css("pointer-events", "none");
            $("#selected_option").hide();
            $("#eliminar").hide();
            $("#actualizar").hide();
            $("#actualizar2").show();
        }

        function guardar()
        {
            var date = $('#date').val();
            var title = $('#title').val();
            var description = $('#description').val();
            var priority_id = $('#priority_id').val();
            var for_admin = $('input[name=para]:checked').val();
            var gerente_id = $('#gerente_id').val();
            var admin_id = $('#admin_id').val();
            var administrativo_id = $('#administrativo_id').val();
            var user_id = $('#user_id').val();

            if(date == ''){
                error_message('Ingrese/seleccione la fecha')
            }else if(title == ''){
                error_message('Ingrese el titulo')
            }else if(description == ''){
                error_message('Ingresa la descripción')
            }else if(!for_admin){
                error_message('Selecciona para quien desea crear la tarea')
            }else if(gerente_id == '' && for_admin == 1){
                error_message('Seleccione el gerente')
            }else if(admin_id == '' && for_admin == 2){
                error_message('Seleccione el administrador')
            }else if(user_id == '' && for_admin == 3){
                error_message('Seleccione el jefe de huerto')
            }else if(administrativo_id == '' && for_admin == 4){
                error_message('Seleccione el administrativo')
            }else if(priority_id == ''){
                error_message('Seleccione la prioridad')
            }else{
                if(for_admin == 1){
                    user_id = gerente_id;
                }else if(for_admin == 2){
                    user_id = admin_id;
                }else if(for_admin == 4){
                    user_id = administrativo_id;
                }else if(for_admin == 0){
                    user_id = '';
                }
                $.ajax({
                    url: "{{route('homework.create')}}",
                    type: "POST",
                    data: { date, title, description, user_id, priority_id, for_admin },
                    success: function(data){
                        if(data.success){
                            success_message('Insertado correctamente')
                        }else{
                            error_message('Ocurrio un error interno')
                        }
                    }
                })
            }
        }

        function actualizar()
        {
            var date        = $('#edit_date').val();
            var title       = $('#edit_title').val();
            var description = $('#edit_description').val();
            var for_admin   = $('input[name=edit_para]:checked').val();
            var gerente_id = $('#edit_gerente_id').val();
            var admin_id    = $('#edit_admin_id').val();
            var user_id     = $('#edit_user_id').val();
            var administrativo_id   = $('#edit_administrativo_id').val();
            var priority_id = $('#edit_priority_id').val();
            var id          = $('#id').val();
            var url         = "{{route('homework.update', ":id")}}";
            url             = url.replace(":id", id);
            var comment     = $('#edit_comment').val();
            if($('#status').prop('checked')) {
                var status = 1;
            }else{
                var status = 0;
            }
            if($('#view').val() == 1){
                if(for_admin == "1"){
                    gerente_id = "{{auth()->user()->id}}";
                }else if(for_admin == "2"){
                    admin_id = "{{auth()->user()->id}}"
                }else if(for_admin == "4"){
                    administrativo_id = "{{auth()->user()->id}}";
                }else if(for_admin == "0"){
                    user_id = "{{auth()->user()->id}}";
                }
            }

            if(date == ''){
                error_message('Ingrese/seleccione la fecha')
            }else if(title == ''){
                error_message('Ingrese el titulo')
            }else if(description == ''){
                error_message('Ingresa la descripción')
            }else if(!for_admin){
                error_message('Selecciona para quien desea crear la tarea')
            }else if(gerente_id == '' && for_admin == 1){
                error_message('Seleccione el gerente')
            }else if(admin_id == '' && for_admin == 2){
                error_message('Seleccione el administrador')
            }else if(user_id == '' && for_admin == 3){
                error_message('Seleccione el jefe de huerto')
            }else if(administrativo_id == '' && for_admin == 4){
                error_message('Seleccione el administrativo')
            }else if(priority_id == ''){
                error_message('Seleccione la prioridad')
            }else{
                if(for_admin == 1){
                    user_id = gerente_id;
                }else if(for_admin == 2){
                    user_id = admin_id;
                }else if(for_admin == 4){
                    user_id = administrativo_id;
                }else if(for_admin == 0){
                    user_id = "{{auth()->user()->id}}";
                }
                var data = {
                    date: date,
                    title: title,
                    description: description,
                    user_id: user_id,
                    priority_id: priority_id,
                    for_admin: for_admin,
                    comment: comment,
                    status: status
                }
                $.ajax({
                    url: url,
                    type: "PUT",
                    data: data,
                    success: function(response){
                        if(response.success){
                            success_message('Actualizado correctamente')
                        }else{
                            error_message('Ocurrio un error interno');
                        }
                    }
                })
            }
        }

        function actualizar2()
        {
            var comment    = $('#edit_comment').val();
            if($('#status').prop('checked')) {
                var status = 1;
            }else{
                var status = 0
            }
            var id = $('#id').val();
            var url = "{{route('homework.status', ":id")}}";
            url = url.replace(":id", id);
            $.ajax({
                    url: url,
                    type: "PUT",
                    data: { status, comment },
                    success: function(response){
                        if(response.success){
                            if(status == 0){
                                var message = 'Tarea cambiada a pendiente';
                            }else{
                                var message = 'Tarea marcada como realizada';
                            }
                            success_message(message, true)
                        }else{
                            error_message('Ocurrio un error interno');
                        }
                    }
            })
        }

        function eliminar()
        {
            Swal.fire({
            title: '¿Esta seguro de que desea eliminar el registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirmar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var id = $('#id').val();
                    var url = "{{route('homework.delete', ":id")}}";
                    url = url.replace(":id", id);
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        success: function(data){
                            if(data.success){
                                success_message('Eliminado correctamente')
                            }else{
                                error_message('Ocurrio un error interno');
                            }
                        }
                    })
                }
            })
        }

        function error_message(title)
        {
            Swal.fire({
                type:'error',
                title: title,
            });
        }

        function success_message(title, reload = true)
        {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: title,
                showConfirmButton: false,
                timer: 500
            }).then(function(){
                if(reload == true){
                    window.location = "{{ route('dashboard') }}";
                }
            })
        }

        function getUsers(role, atrib = '#user_id', user_id = null)
        {
            var url = "{{route('usuarios.by.role')}}";
            $.ajax({
                url: url,
                type: "POST",
                data: { role },
                dataType: "json",
                success: function(response){
                    $(atrib).empty();
                    $(atrib).append("<option value=''>Seleccione</option>");
                    $.each(response, function (index, value) {
                        var selected = value.id == user_id ? 'selected' : '';
                        $(atrib).append(`<option value="${value.id}" ${selected}>${value.name}</option>`);
                    })
                }
            });
        }

        function selectI()
        {
            $('#gerente_id').empty();
            $('#gerente_id').append("<option value=''>Seleccione</option>");
            $('#administrativo_id').empty();
            $('#administrativo_id').append("<option value=''>Seleccione</option>");
            $('#admin_id').empty();
            $('#admin_id').append("<option value=''>Seleccione</option>");
            $('#user_id').empty();
            $('#user_id').append("<option value=''>Seleccione</option>");

            $('#form-gerente').hide();
            $('#form-administrativo').hide();
            $('#form-administrador').hide();
            $('#form-jefe').hide();
        }

        function selectGerente()
        {
            $('#administrativo_id').empty();
            $('#administrativo_id').append("<option value=''>Seleccione</option>");
            $('#admin_id').empty();
            $('#admin_id').append("<option value=''>Seleccione</option>");
            $('#user_id').empty();
            $('#user_id').append("<option value=''>Seleccione</option>");

            $('#form-gerente').show();
            $('#form-administrativo').hide();
            $('#form-administrador').hide();
            $('#form-jefe').hide();

            $('#gerente_id').val('');
            getUsers('Gerente', '#gerente_id');
        }

        function selectAdministrativo()
        {
            $('#gerente_id').empty();
            $('#gerente_id').append("<option value=''>Seleccione</option>");
            $('#admin_id').empty();
            $('#admin_id').append("<option value=''>Seleccione</option>");
            $('#user_id').empty();
            $('#user_id').append("<option value=''>Seleccione</option>");

            $('#form-gerente').hide();
            $('#form-administrativo').show();
            $('#form-administrador').hide();
            $('#form-jefe').hide();

            $('#administrativo_id').val('');
            getUsers('Administrativo', '#administrativo_id');
        }

        function selectAdmin()
        {
            $('#gerente_id').empty();
            $('#gerente_id').append("<option value=''>Seleccione</option>");
            $('#administrativo_id').empty();
            $('#administrativo_id').append("<option value=''>Seleccione</option>");
            $('#user_id').empty();
            $('#user_id').append("<option value=''>Seleccione</option>");

            $('#form-gerente').hide();
            $('#form-administrativo').hide();
            $('#form-jefe').hide();
            $('#form-administrador').show();

            $('#admin_id').val('');
            getUsers('Admin', '#admin_id')
        }

        function selectJefe()
        {
            $('#gerente_id').empty();
            $('#gerente_id').append("<option value=''>Seleccione</option>");
            $('#administrativo_id').empty();
            $('#administrativo_id').append("<option value=''>Seleccione</option>");
            $('#admin_id').empty();
            $('#admin_id').append("<option value=''>Seleccione</option>");

            $('#form-gerente').hide();
            $('#form-administrativo').hide();
            $('#form-administrador').hide();
            $('#form-jefe').show();

            $('#user_id').val('');
            getUsers('JH', '#user_id');
        }

        function editSelectI()
        {
            $('#edit_gerente_id').empty();
            $('#edit_gerente_id').append("<option value=''>Seleccione</option>");
            $('#edit_administrativo_id').empty();
            $('#edit_administrativo_id').append("<option value=''>Seleccione</option>");
            $('#edit_admin_id').empty();
            $('#edit_admin_id').append("<option value=''>Seleccione</option>");
            $('#edit_user_id').empty();
            $('#edit_user_id').append("<option value=''>Seleccione</option>");

            $('#edit-form-gerente').hide();
            $('#edit-form-administrativo').hide();
            $('#edit-form-administrador').hide();
            $('#edit-form-jefe').hide();
        }

        function editSelectGerente(user_id)
        {
            $('#edit_administrativo_id').empty();
            $('#edit_administrativo_id').append("<option value=''>Seleccione</option>");
            $('#edit_admin_id').empty();
            $('#edit_admin_id').append("<option value=''>Seleccione</option>");
            $('#edit_user_id').empty();
            $('#edit_user_id').append("<option value=''>Seleccione</option>");

            $('#edit-form-administrativo').hide();
            $('#edit-form-administrador').hide();
            $('#edit-form-jefe').hide();

            if(user_id == '{{auth()->user()->id}}'){
                $('#edit_gerente_id').empty();
                $('#edit_gerente_id').append("<option value=''>Seleccione</option>");
                $('#edit-form-gerente').hide();
            }else{
                $('#edit-form-gerente').show();
                $('#edit_gerente_id').val('');
                getUsers('Gerente', '#edit_gerente_id', user_id);
            }
        }

        function editSelectAdministrativo(user_id)
        {
            $('#edit_gerente_id').empty();
            $('#edit_gerente_id').append("<option value=''>Seleccione</option>");
            $('#edit_admin_id').empty();
            $('#edit_admin_id').append("<option value=''>Seleccione</option>");
            $('#edit_user_id').empty();
            $('#edit_user_id').append("<option value=''>Seleccione</option>");

            $('#edit-form-gerente').hide();
            $('#edit-form-administrador').hide();
            $('#edit-form-jefe').hide();

            if(user_id == '{{auth()->user()->id}}'){
                $('#edit_administrativo_id').empty();
                $('#edit_administrativo_id').append("<option value=''>Seleccione</option>");
                $('#edit-form-administrativo').hide();
            }else{
                $('#edit-form-administrativo').show();
                $('#edit_administrativo_id').val('');
                getUsers('Administrativo', '#edit_administrativo_id', user_id);
            }
        }

        function editSelectAdmin(user_id)
        {
            $('#edit_gerente_id').empty();
            $('#edit_gerente_id').append("<option value=''>Seleccione</option>");
            $('#edit_administrativo_id').empty();
            $('#edit_administrativo_id').append("<option value=''>Seleccione</option>");
            $('#edit_user_id').empty();
            $('#edit_user_id').append("<option value=''>Seleccione</option>");

            $('#edit-form-gerente').hide();
            $('#edit-form-administrativo').hide();
            $('#edit-form-jefe').hide();

            if(user_id == '{{auth()->user()->id}}'){
                $('#edit_admin_id').empty();
                $('#edit_admin_id').append("<option value=''>Seleccione</option>");
                $('#edit-form-administrador').hide();
            }else{
                $('#edit-form-administrador').show();
                $('#edit_admin_id').val('');
                getUsers('Admin', '#edit_admin_id', user_id);
            }
        }

        function editSelectJefe(user_id)
        {
            $('#edit_gerente_id').empty();
            $('#edit_gerente_id').append("<option value=''>Seleccione</option>");
            $('#edit_administrativo_id').empty();
            $('#edit_administrativo_id').append("<option value=''>Seleccione</option>");
            $('#edit_admin_id').empty();
            $('#edit_admin_id').append("<option value=''>Seleccione</option>");

            $('#edit-form-gerente').hide();
            $('#edit-form-administrativo').hide();
            $('#edit-form-administrador').hide();

            if(user_id == '{{auth()->user()->id}}'){
                $('#edit_user_id').empty();
                $('#edit_user_id').append("<option value=''>Seleccione</option>");
                $('#edit-form-jefe').hide();
            }else{
                $('#edit-form-jefe').show();
                $('#edit_user_id').val('');
                getUsers('JH', '#edit_user_id', user_id);
            }
        }
    </script>
    @endpush
</x-app-layout>
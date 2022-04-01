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
                            if(!"{{ auth()->user()->hasRole('JH') }}"){
                                $("#selected_option").show();
                                $("#actualizar").show();
                                $("#eliminar").show();
                                $("#actualizar2").hide();
                                $("#eliminar").show();
                                $("#title_modal").text('Editar tarea');
                                $("#edit_date").prop('readonly', false);
                                $("#edit_title").prop('readonly', false);
                                $("#edit_description").prop('readonly', false);
                                $("#edit_priority_id").css("pointer-events", "auto");
                            }
                            if(response.homework.for_admin == 1){
                                $('#edit_gerente').prop('checked', true);
                                $('#edit-form-gerente').show();
                                $('#edit-form-administrador').hide();
                                $('#edit-form-jefe').hide();
                                $('#edit_gerente_id').val(response.homework.user_id);
                                if("{{ auth()->user()->hasRole('Gerente') }}"){
                                    hideElements();
                                }
                            }else if(response.homework.for_admin == 2){
                                $('#edit-form-gerente').show();
                                $('#edit-form-administrador').show();
                                $('#edit-form-jefe').hide();
                                $('#edit_admin').prop('checked', true);
                                $('#edit_gerente_id').val(response.gerente_id);
                                getAdmins(response.gerente_id, '#edit_admin_id', response.homework.user_id);
                                if("{{ auth()->user()->hasRole('Admin') }}"){
                                    hideElements();
                                }
                            }else{
                                $('#edit-form-gerente').show();
                                $('#edit-form-administrador').show();
                                $('#edit-form-jefe').show();
                                $('#edit_jh').prop('checked', true);
                                $('#edit_gerente_id').val(response.gerente_id);
                                getAdmins(response.gerente_id, '#edit_admin_id', response.admin_id)
                                getJefes(response.admin_id, '#edit_user_id', response.homework.user_id)
                                if("{{ auth()->user()->hasRole('JH') }}"){
                                    $("#actualizar2").show()
                                }
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
                        $("#actualizar").show();   e.el.prepend(i);
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
            var user_id = $('#user_id').val();
            var priority_id = $('#priority_id').val();
            var for_admin = $('input[name=para]:checked').val();
            var admin_id = $('#admin_id').val();
            var gerente_id = $('#gerente_id').val();

            if($('input[name=para]').length <= 0){
                for_admin = 3;
            }
            if($('#gerente_id').length <= 0){
                gerente_id = '';
            }

            if(date == ''){
                error_message('Ingrese/seleccione la fecha')
            }else if(title == ''){
                error_message('Ingrese el titulo')
            }else if(description == ''){
                error_message('Ingresa la descripción')
            }else if(!for_admin && "{{ auth()->user()->hasRole('Administrativo|Gerente') }}"){
                error_message('Selecciona para quien desea crear la tarea')
            }else if(gerente_id == '' && (for_admin == 1 || for_admin == 2 || for_admin == 3 ) && "{{ auth()->user()->hasRole('Administrativo') }}"){
                error_message('Seleccione el gerente')
            }else if(admin_id == '' && (for_admin == 2 || for_admin == 3) && "{{ auth()->user()->hasRole('Administrativo|Gerente') }}"){
                error_message('Seleccione el administrador')
            }else if(for_admin == 3 && user_id == ''){
                error_message('Seleccione el jefe de huerto')
            }else if(priority_id == ''){
                error_message('Seleccione la prioridad')
            }else{
                if(for_admin == 1){
                    user_id = gerente_id;
                }else if(for_admin == 2){
                    user_id = admin_id;
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
            var admin_id    = $('#edit_admin_id').val();
            var user_id     = $('#edit_user_id').val();
            var gerente_id  = $('#edit_gerente_id').val();
            var priority_id = $('#edit_priority_id').val();
            var id          = $('#id').val();
            var url         = "{{route('homework.update', ":id")}}";
            url             = url.replace(":id", id);
            if($('input[name=edit_para]').length <= 0){
                for_admin = 3;
            }
            var comment    = $('#edit_comment').val();
            if($('#status').prop('checked')) {
                var status = 1;
            }else{
                var status = 0
            }

            if(date == ''){
                error_message('Ingrese/seleccione la fecha')
            }else if(title == ''){
                error_message('Ingrese el titulo')
            }else if(description == ''){
                error_message('Ingresa la descripción')
            }else if(!for_admin && "{{ auth()->user()->hasRole('Administrativo|Gerente') }}"){
                error_message('Selecciona para quien desea crear la tarea')
            }else if(gerente_id == '' && (for_admin == 1 || for_admin == 2 || for_admin == 3 ) && "{{ auth()->user()->hasRole('Administrativo') }}"){
                error_message('Seleccione el gerente')
            }else if(admin_id == '' && (for_admin == 2 || for_admin == 3) && "{{ auth()->user()->hasRole('Administrativo|Gerente') }}"){
                error_message('Seleccione el administrador')
            }else if(for_admin == 3 && user_id == ''){
                error_message('Seleccione el jefe de huerto')
            }else if(priority_id == ''){
                error_message('Seleccione la prioridad')
            }else{
                if(for_admin == 1){
                    user_id = gerente_id;
                }else if(for_admin == 2){
                    user_id = admin_id;
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

        //ESTO ESTA PENDIENTE YA QUE HAY QUE ARMAR RELACION DE ADMINISTRATIVO WITH GERENTES Y GERENTES WITH ADMIN
        function getAdmins(gerente_id, atrib = '#admin_id', user_id = null)
        {
            if(gerente_id != '')
            {
                var url = "{{route('admins.gerente', ":id")}}";
                url = url.replace(":id", gerente_id);
                $.ajax({
                    url: url,
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
        }

        function getJefes(admin_id, atrib = '#user_id', user_id = null)
        {
            if(admin_id != ''){
                var url = "{{route('jefes.admin', ":id")}}";
                url = url.replace(":id", admin_id);
                $.ajax({
                    url: url,
                    dataType: "json",
                    success: function(response){
                        $(atrib).empty();
                        $(atrib).append("<option value=''>Seleccione</option>");

                        $.each(response, function (index, value) {
                            var selected = value.jefe.id == user_id ? 'selected' : '';
                            $(atrib).append(`<option value="${value.jefe.id}" ${selected}>${value.jefe.name}</option>`);
                        })
                    }
                });
            }
        }

        function selectGerente()
        {
            $('#admin_id').empty();
            $('#admin_id').append("<option value=''>Seleccione</option>");
            $('#user_id').empty();
            $('#user_id').append("<option value=''>Seleccione</option>");
            $('#form-administrador').hide();
            $('#form-jefe').hide();
            $('#form-gerente').show();
            $('#gerente_id').val('');
        }

        function selectAdmin()
        {
            $('#user_id').empty();
            $('#user_id').append("<option value=''>Seleccione</option>");
            $('#form-jefe').hide();
            if("{{auth()->user()->hasRole('Administrativo')}}"){
                $('#form-gerente').show();
                $('#gerente_id').val('');
            }
            $('#form-administrador').show();
            $('#admin_id').val('');
        }

        function selectJefe()
        {
            if("{{auth()->user()->hasRole('Administrativo')}}"){
                $('#form-gerente').show();
            }
            $('#form-jefe').show();
            $('#form-administrador').show();
            $('#admin_id').val('');
        }

        function editSelectAdmin()
        {
            $('#edit_user_id').empty();
            $('#edit_user_id').append("<option value=''>Seleccione</option>");
            $('#edit-form-jefe').hide();
            $('#edit-form-administrador').show();
            $('#edit_admin_id').val('');
        }

        function editSelectJefe()
        {
            $('#edit-form-jefe').show();
            $('#edit-form-administrador').show();
            $('#edit_admin_id').val('');
            $('#edit_user_id').empty();
            $('#edit_user_id').append("<option value=''>Seleccione</option>");
        }
    </script>
    @endpush
</x-app-layout>
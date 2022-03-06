<x-app-layout>
    @include('components.tareas.crear-tarea')
    @include('components.tareas.editar-tarea')
    @role('Admin')
    <button type="button" class="btn btn-primary mb-3 btn-lg"  data-bs-toggle="modal" data-bs-target="#create-homework">
        Agregar
    </button>
    @endrole

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
                eventStartEditable: "{{ auth()->user()->hasRole('Admin') }}" ? true : false,
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
                            $('#edit_date').val(response.date);
                            $('#edit_title').val(response.title);
                            $('#edit_description').val(response.description);
                            $('#edit_user_id').val(response.user_id);
                            $('#edit_priority_id').val(response.priority_id);
                        }
                    });
                },

            });
            calendar.render();
        });

        function guardar()
        {
            var date = $('#date').val();
            var title = $('#title').val();
            var description = $('#description').val();
            var user_id = $('#user_id').val();
            var priority_id = $('#priority_id').val();

            $.ajax({
                url: "{{route('homework.create')}}",
                type: "POST",
                data: { date, title, description, user_id, priority_id },
                success: function(data){
                    if(data.success){
                        success_message('Insertado correctamente')
                    }else{
                        error_message('Ocurrio un error interno')
                    }
                }
            })
        }

        function actualizar()
        {
            var date = $('#edit_date').val();
            var title = $('#edit_title').val();
            var description = $('#edit_description').val();
            var user_id = $('#edit_user_id').val();
            var priority_id = $('#edit_priority_id').val();
            var id = $('#id').val();
            var url = "{{route('homework.update', ":id")}}";
            url = url.replace(":id", id);
            $.ajax({
                url: url,
                type: "PUT",
                data: { date, title, description, user_id, priority_id },
                success: function(data){
                    if(data.success){
                        success_message('Actualizado correctamente')
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

        function success_message(title)
        {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: title,
                showConfirmButton: false,
                timer: 500
            }).then(function(){
                window.location = "{{ route('dashboard') }}";
            })
        }
    </script>
    @endpush
</x-app-layout>
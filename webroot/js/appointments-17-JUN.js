$(document).ready(function () {
    $('.addNewAppmtBtn').click(function () {
        if ($(this).attr('hideDatePicker') == "yes") {
            $('.TimeDivPopup').show();
            $('.datePickerDivPopup').hide();
            $(this).attr('hideDatePicker', "no");
        } else {
            $('.TimeDivPopup').hide();
            $('.datePickerDivPopup').show();
        }
    });
    /*FULL CALENDER CONFIGURTION*/
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var defaultDate = yyyy + '-' + mm + '-' + dd;
    var day = today.getDay();
    $('#calendar').fullCalendar({// set header and view of calander
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,basicDay'
        },
        defaultView: 'agendaWeek',
        firstDay: day - 1,
        minTime: "09:00:00",
        maxTime: "23:00:00",
        columnFormat: "ddd D - M",
        slotDuration: "00:15:00",
        allDaySlot: false,
        views: {
            agendaWeek: {
                type: 'agenda',
                duration: {days: 7},
                buttonText: 'week',
                titleFormat: 'D MMM, YYYY'
            }
        },
        dayClick: function (date, jsEvent, view) {
            // Hide toolbar
            $('.tooltiptopicevent').hide();
            // Set POPUP TIME
            $('.timeSpan').html(date.format('DD MMM, YYYY [|] h:mm A'));
            //Format 2017-01-12T10:30:00
            $('.hiddenstart').val(date.format('YYYY-MM-DD') + 'T' + date.format('HH:mm:ss'));
            $('.tooltiptopicevent').hide();
            /*Open Popup*/
            $('.addNewAppmtBtn').attr('hideDatePicker', "yes");
            $('.addNewAppmtBtn').click();
        },
        lazyFetching: true,
        timeFormat: 'h(:mm)t',
        defaultDate: defaultDate,
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        defaultTimedEventDuration: "00:15:00",
        eventBackgroundColor: "#65450E",
        eventBorderColor: "#65450E",
        events: function (start, end, timezone, callback) { // getall appointments and set it in calander
            jQuery.ajax({
                url: $('.getAllAppointments').val(),
                type: 'POST',
                dataType: 'json',
                data: {
                    start: start.format(),
                    end: end.format(),
                },
                success: function (doc, data) {
                    var events = [];
                    getdatafromdb(doc, data);
                    callback(events);
                }
            });
        },
        eventDrop: function (event, delta, revertFunc, data) { // save start time when change the start time of appointment
            var formData = data;
            var formUrl = $('.dropstartevent').val();
            var id = event.id;
            var url = formUrl + '/' + id;
            var start = event.start.format();
            $.ajax({
                type: 'POST',
                url: url,
                data: formData + '&' + $.param({'start': start}),
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        var formUrl = $('.getlastedited').val();
                        var id = event.id;
                        var url = formUrl + '/' + id;
                        $('#calendar').fullCalendar('removeEvents', id);
                        jQuery.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            success: function (doc) {
                                $('#calendar').fullCalendar('removeEvents', id);
                                getdatafromdb(doc, data);
                            },
                            error: function (data) {
                                (data);
                            }
                        }).done(function (response) {
                            $('#AppointmentModal').modal('hide');
                        });
                    }
                },
                error: function (data) {
                    (data);
                }

            });
        },
        eventResize: function (event, delta, revertFunc, data) { // save end time when change the end time of appointment
            var formData = data;
            var formUrl = $('.dropendevent').val();
            var id = event.id;
            var url = formUrl + '/' + id;
            var end = event.end.format();
            $.ajax({
                type: 'POST',
                url: url,
                data: formData + '&' + $.param({'end_time': end}),
                dataType: 'json',
                success: function (data) {
                    if (data.status) {
                        var formUrl = $('.getlastedited').val();
                        var id = event.id;
                        var url = formUrl + '/' + id;
                        $('#calendar').fullCalendar('removeEvents', id);
                        jQuery.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            success: function (doc) {
                                $('#calendar').fullCalendar('removeEvents', id);
                                getdatafromdb(doc, data);
                            },
                            error: function (data) {
                                (data);
                            }
                        }).done(function (response) {
                            $('#AppointmentModal').modal('hide');
                        });
                    }
                },
                error: function (data) {
                    (data);
                }
            });
        },
        eventClick: function (data, event, view) {  // display mousehover popup
            $('.copypopup').remove();
            tooltip = $("#pt_popup").html();
            tooltip = tooltip.replace("##CLASS##", "copypopup");
            tooltip = tooltip.replace("##ID##", data.id);
            tooltip = tooltip.replace("##ALLID##", data.id);
            tooltip = tooltip.replace("##PT_NAME##", data.title);
            tooltip = tooltip.replace("##APPT_DATE##", data.startdate);
            tooltip = tooltip.replace("##PT_MOBILE##", data.mobile);
            tooltip = tooltip.replace("##APPT_TIME_DURATION##", data.starttime + " | " + data.durationtime);
            tooltip = tooltip.replace("##DR_NAME##", data.doctorname);
            tooltip = tooltip.replace("##CLINIC_NAME##", "MY CLINIC");
            if(data.selectworktodo != ""){
                tooltip = tooltip.replace("##WORKTODO##", data.selectworktodo);
            } else {
                tooltip = tooltip.replace("##WORKTODO##", data.worktodo);
            }
            
            tooltip = tooltip.replace("##REVERT##", data.revertstatus);
            tooltip = tooltip.replace("##NOTE##", data.note);
            $("body").append(tooltip);
            
            //remove
            $(this).css('z-index', 10000);
            $('.tooltiptopicevent').fadeIn('500');
            $('.tooltiptopicevent').fadeTo('10', 1.9);
            
            $(this).mouseover(function (e) {
                $(this).css('z-index', 10000);
                $('.tooltiptopicevent').fadeIn('500');
                $('.tooltiptopicevent').fadeTo('10', 1.9);
            
            }).mousemove(function (e) {
                $('.tooltiptopicevent').css('top', e.pageY + 10);
                $('.tooltiptopicevent').css('left', e.pageX + 20);
            });
        },
        eventMouseout: function (data, event, view) {
            // $(this).css('z-index', 0);
            $('.copypopup').remove();
        },
        eventRender: function (event, element) { // if worktodo is selected from dropdown (or add in textbox) than dispaly it in event
            console.log(event);
           /* if (event.selectworktodo != '') {
                element.find('.fc-title').append("-" + event.selectworktodo);
            } else if (event.worktodo != '') {
                element.find('.fc-title').append("-" + event.worktodo);
            } */
            
             if (event.worktodo != '') {
                element.find('.fc-title').append("-" + event.worktodo);
            } else 
                if (event.selectworktodo != '') {
                element.find('.fc-title').append("-" + event.selectworktodo);
            } 
 
        }
    });
    /***************** ADD APPOIMENT POPUP ****************/
    $('.AppDatePopup').datepicker({// datepicker for date
        dateFormat: 'dd-mm-yy',
        minDate: 0,
    });
    var $select = $('#SELECT-WORK-TO-DO').selectize({// Work to do selectize dropdown
        delimiter: ',',
        persist: false,
        create: false
    });
    var $select = $('#SELECT-DOCTOR').selectize({// Doctors selectize dropdown
        delimiter: ',',
        persist: false,
        create: false
    });
    var $select = $('#SELECT-DURATION').selectize({// Duration selectize dropdown
        delimiter: ',',
        persist: false,
        create: false
    });
    var $select = $('#hour').selectize({// Hours selectize dropdown
        delimiter: ',',
        persist: false,
        create: false
    });
    var $select = $('#min').selectize({// Minutes selectize dropdown
        delimiter: ',',
        persist: false,
        create: false
    });
    var $select = $('#ampm').selectize({// AM or PM selectize dropdown
        delimiter: ',',
        persist: false,
        create: false
    });
    var $select = $('#SELECT-PATIENT').selectize({// auto populate data of patient when its select from dropdown
        delimiter: ',',
        persist: false,
        create: true,
       
        onChange: function (id) {
            // $('.TimeDivPopup').hide();
            // $('.datePickerDivPopup').show();
            if (!id.length)
                return;
            var requesturl = $('#getpatient').val();
            $.ajax({
                type: "POST",
                url: requesturl,
                data: {id: id, },
                dataType: "json",
                success: function (data) {
                    if (data.success) {
                        console.log(data.patientresult);
                        //console.log(data.result);
                        if (data.patientresult != null) {
//                            $('#SELECT-DOCTOR').selectize()[0].selectize.setValue(data.patientresult.reff_by, true);
                            $('#patientName').val(data.patientresult.firstname);
                            $('#contactMobile').val(data.patientresult.phone);
//                            $('#email').val(data.patientresult.email);
//                            var date = new Date();
//                            $('.AppDatePopup').val((date.getDate()) + '-' + (date.getMonth() + 1) + '-' + date.getFullYear());
                            if (data.patientresult.note != '') {
                                $('#note').val(data.patientresult.note);
                            } else {
                                $('#note').val(data.patientresult.dr_note);
                            }
                        }

                    } else {

                    }
                },
                error: function () {
                }
            });
        }
    });
    $('.addNewAppmtBtn ').click(function () { // clear data when click on add new appointment
        $('.patienttextbox').hide();
        $('.patientdropdown').show();
        $('#ADDFORM').html('ADD');
        document.getElementById("addNewAppoimentForm").reset();
        //cleardropdown();
        var now = new Date(); //namrata
        var today = now.getDate()  + '-' + (now.getMonth() + 1) + '-' + now.getFullYear(); //namrata
        $('.AppDatePopup').val(today);  //namrata
        $('#addNewAppoimentForm').find('#editapps').attr('id', 'saveappointments');
        $('#addNewAppoimentForm').find('#editappsfromptfile').attr('id', 'saveappointments');        
        return true;
    });
    $('#clearformforindex').click(function () { // clear data when click on add close button
        document.getElementById("addNewAppoimentForm").reset();
        //cleardropdown();
        $('#AppointmentModal').modal('hide');      
    });
    $('.fc-widget-content').click(function () { // clear data when click on calander for add new appointment
        $('.patienttextbox').hide();
        $('.patientdropdown').show();       
        document.getElementById("addNewAppoimentForm").reset();
        //cleardropdown();
        $('#addNewAppoimentForm').find('#editapps').attr('id', 'saveappointments');
        $('#addNewAppoimentForm').find('#editappsfromptfile').attr('id', 'saveappointments');
        return true;

    });
    $('#addNewAppoimentForm').submit(function () {
        var $inputs = $('#addNewAppoimentForm :input');
        // not sure if you wanted this, but I thought I'd add it.
        // get an associative array of just the values.
        var values = {};
        $inputs.each(function () {
            values[this.name] = $(this).val();
        });
        console.log(values['appdate'] + values['hour'] + values['min'] + values['ampm']);
        return false;
    });
    $(document).on("click", "button#saveappointments", function () { // save appointment
        var formData = $('#addNewAppoimentForm').serialize();
        var formUrl = $('.addapointments').val();
        $.ajax({
            type: 'POST',
            url: formUrl,
            data: formData,
            dataType: 'json',
             beforeSend:
                function () {
                    $('#appointment_loader').show();
                },
            success: function (data) {
                if (data.status) {
                    jQuery.ajax({
                        url: $('.getlastAppointments').val(),
                        type: 'POST',
                        dataType: 'json',
                       
                        success: function (doc) {
                            getdatafromdb(doc, data);
                            $('#appointment_loader').hide();
                        },
                        error: function (data) {
                            (data);
                        }

                    }).done(function (response) {
                        $('#AppointmentModal').modal('hide');
                        jQuery("#AppointmentBookedSuccessfully").modal('show'); //namrata
                    });
                } else {
                    if(data.result == 'Booked') {
                         jQuery("#AppointmentBooked").modal('show'); //namrata
                    }
                    else if (data.result == 'No Patient') {
                        jQuery("#PatientSelect").modal('show'); //namrata
                    } else {
                        
                    }
                }
            },
            error: function (data) {
                (data);
            }

        });
    });
    $(document).on("click", "button#editapps", function () { // edit appopintment by clicking on reschedule button
        var formData = $('#addNewAppoimentForm').serialize();
        var formUrl = $('.hiddenediturl').val();
        var id = $('#appointment_id').val();
        var url = formUrl + '/' + id;
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    var formUrl = $('.getlastedited').val();
                    var id = $('#appointment_id').val();
                    var url = formUrl + '/' + id;
                    $('#calendar').fullCalendar('removeEvents', id);
                    jQuery.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        success: function (doc) {
                            $('#calendar').fullCalendar('removeEvents', id);
                            getdatafromdb(doc, data);
                        },
                        error: function (data) {
                            (data);
                        }
                    }).done(function (response) {
                        $('#AppointmentModal').modal('hide');
                        jQuery("#AppointmentBookedSuccessfully").modal('show'); //namrata

                    });
                }
            },
            error: function (data) {
                (data);
            }

        });
    });
    $('#editappsfromptfile').click(function () { // edit appointment from patient file
        var formData = $('#addNewAppoimentForm').serialize();
        var formUrl = $('.hiddenediturl').val();
        var id = $('.editappsfromptfile').val();
        var url = formUrl + '/' + id;
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    var formUrl = $('.getlastedited').val();
                    var id = $('.editappsfromptfile').val();
                    var url = formUrl + '/' + id;
                    $('#calendar').fullCalendar('removeEvents', id);
                    jQuery.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        success: function (doc) {
                            $('#calendar').fullCalendar('removeEvents', id);
                            getdatafromdb(doc, data);
                        },
                        error: function (data) {
                            (data);
                        }
                    }).done(function (response) {
                        $('#AppointmentModal').modal('hide');
                    });
                }
            },
            error: function (data) {
                (data);
            }

        });
    });
    $(document).on("click", "button.cancelappointment", function () { // Change color of event to cancel appointment
        var formData = $('#addNewAppoimentForm').serialize();
        var formUrl = $('.cancelevent').val();
        var id = $(this).parents('.customeditdelete').find('.editdeleteid').val();
        var url = formUrl + '/' + id;

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {

                if (data.status) {
                    var formUrl = $('.getlastedited').val();
                    var url = formUrl + '/' + id;
                    $('#calendar').fullCalendar('removeEvents', id);
                    jQuery.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        success: function (doc) {
                            $('#calendar').fullCalendar('removeEvents', id);
                            getdatafromdb(doc, data);
                        },
                        error: function (data) {
                            (data);
                        }

                    }).done(function (response) {
                        $('#AppointmentModal').modal('hide');
                    });
                }
            },
            error: function (data) {
                (data);
            }

        });
    });
    $(document).on("click", "button.revertappointment", function () { // Change color of event to revert appointment
        var formData = $('#addNewAppoimentForm').serialize();
        var formUrl = $('.revertevent').val();
        var id = $(this).parents('.customeditdelete').find('.editdeleteid').val();
        var url = formUrl + '/' + id;

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {

                if (data.status) {
                    var formUrl = $('.getlastedited').val();
                    var url = formUrl + '/' + id;
                    $('#calendar').fullCalendar('removeEvents', id);
                    jQuery.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        success: function (doc) {
                            $('#calendar').fullCalendar('removeEvents', id);
                            getdatafromdb(doc, data);
                        },
                        error: function (data) {
                            (data);
                        }

                    }).done(function (response) {
                        $('#AppointmentModal').modal('hide');
                    });
                }
            },
            error: function (data) {
                (data);
            }

        });
    });
    /*$(document).on("click", "a.deleteappointment", function () { // delete appointment
        var id = $(this).attr('data-value');
        var formUrl = $('.hiddendeleteurl').val();
        var url = formUrl + '/' + id;
        // alert(id ); return false;
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status) {
                    $('#calendar').fullCalendar('removeEvents', id);
                    $('.tooltiptopicevent').hide();
                }
            },
            error: function (data) {
                (data);
            }
        });
        $('.tooltiptopicevent').hide();
    }); */
    $(document).on("click", "button.editappointment", function () { // auto populate data by clicking on reschedule button
        $('#AppointmentModal').modal('show');
        $('.TimeDivPopup').hide();
        $('.datePickerDivPopup').show();
        $('.tooltiptopicevent').hide();
        $('#pt_popup').hide();
        $('#ADDFORM').html('EDIT');
        $('#addNewAppoimentForm').find('#saveappointments').attr('id', 'editapps');
        var id = $(this).parents('.customeditdelete').find('.editdeleteid').val();
        var formUrl = $('.hiddengeturl').val();
        var url = formUrl + '/' + id;
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            success: function (data) {
                $('#appointment_id').val(data.result.id);

                $('#SELECT-DURATION').selectize()[0].selectize.setValue(data.result.duration, true);
                $('#SELECT-DOCTOR').selectize()[0].selectize.setValue(data.result.selectdoc, true);
                if (data.result.patientid != '') {
                    $('.patientdropdown').show();
                    $('.patienttextbox').hide();
                    $('#SELECT-PATIENT').selectize()[0].selectize.setValue(data.result.patientid, true);
                } else {
                    $('.patienttextbox').show();
                    $('.patientdropdown').hide();
                    $('#patientname').val(data.result.patientname);
                }
                $('#SELECT-WORK-TO-DO').selectize()[0].selectize.setValue(data.result.selectworktodo, true);
                var d = data.result.start;
                var daterplace = d.replace('T', ' ');
                var date = new Date(daterplace);

                $('.AppDatePopup').val((date.getDate()) + '-' + (date.getMonth() + 1) + '-' + date.getFullYear());
                $('#hour').selectize()[0].selectize.setValue(date.getHours());
                var min = (date.getMinutes() == 0) ? "1" : date.getMinutes();
                $('#min').selectize()[0].selectize.setValue(min);
                var ampm = (date.getHours() >= 12) ? "PM" : "AM";
                $('#ampm').selectize()[0].selectize.setValue(ampm);
                $('#contactMobile').val(data.result.mobile);
                $('#email').val(data.result.email);
                $('#worktodo').val(data.result.worktodo);
                $('#note').val(data.result.note);
                if (data.result.patientsms == '1') {
                    $('#patientsms').prop('checked', true);
                } else {
                    $('#patientsms').prop('checked', false);
                }
                if (data.result.docsms == '1') {
                    $('#docsms').prop('checked', true);
                } else {
                    $('#docsms').prop('checked', false);
                }
                if (data.result.patientemail == '1') {
                    $('#patientemail').prop('checked', true);
                } else {
                    $('#patientemail').prop('checked', false);
                }
                if (data.result.docemail == '1') {
                    $('#docemail').prop('checked', true);
                } else {
                    $('#docemail').prop('checked', false);
                }
            },
            error: function (data) {
                (data);
            }
        });
    });
    $(".getpatienttextboxname").click(function () { // for patient name click on down arraow button show dropdown of patients
        $('.patientdropdown').show();
        $('.patienttextbox').hide();
    });
    $(document).on("click", "button#cancelappointment", function () { // show revert button and hide cancel button
        $('.cancelappointment').hide();
        $('.revertappointment').show();
    });
    $(document).on("click", "button#revertappointment", function () {  // show cancel button and hide revert button
        $('.cancelappointment').show();
        $('.revertappointment').hide();
    });

    function getdatafromdb(doc, data) { // this function is used for get data as per id
        if (!!doc.result.Data) {
           
            $.map(doc.result.Data, function (r) {
                $('#calendar').fullCalendar('renderEvent', {
                    id: r.id,
                    title: r.patientname,
                    mobile: r.mobile,
                    doctorname: r.selectdocname,
                    start: r.start,
                    end: r.end_time,
                    startdate: r.startdate,
                    starttime: r.starttime,
                    durationtime: r.durationtime,
                    selectworktodo: r.selectworktodoname,
                    worktodo: r.worktodo,
                    note: r.note,
                    color: r.colors
                });
            });
        }
    }
    function cleardropdown() {
        $select = $('#SELECT-WORK-TO-DO').selectize();
        control = $select[0].selectize;
        control.clear(true);
        control.close();

        $selecta = $('#SELECT-PATIENT').selectize();
        control = $selecta[0].selectize;
        control.clear(true);
        control.close();    
        
        var $select1 = $("#SELECT-DURATION").selectize();
        var selectize1 = $select1[0].selectize;
        selectize1.setValue("15"); 
        
        var $select2 = $("#hour").selectize();
        var selectize2 = $select2[0].selectize;
        selectize2.setValue("9"); 
        
        var $select3 = $("#min").selectize();
        var selectize3 = $select3[0].selectize;
        selectize3.setValue("1"); 
        
        var $select4 = $("#ampm").selectize();
        var selectize4 = $select4[0].selectize;
        selectize4.setValue("AM"); 
       
        $('#appointment_id').val("");
    }
    $(document).on("click", "button.closepopup", function () { 
        $('.tooltiptopicevent').hide();
    });
    

});
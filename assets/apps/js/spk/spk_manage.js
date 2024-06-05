$(document).ready(function() {
    //lha 
    $(document).on('click', '.reset-form', function() {
        $("input[name='perihal']").val('');
        $("select[name='id_jenis_spk']").val('').trigger("change.select2");
        $("select[name='id_nama_spk']").val('').trigger("change.select2");
        $("input[name='SPPKP']").val('');
        $("select[name='id_jenis_vendor']").val('').trigger("change.select2");
        // $("select[name='LIFNR']").val('').trigger("change.select2");
        $("select[name='id_kualifikasi']").val('').trigger("change.select2");
        $("input[name='LIFNR']").val('');
        $("input[name='CITY1']").val('');
        $("input[name='STRAS']").val('');
		$('#lifnr').val('').trigger('change');
    });

    // //change nama vendor xxxx
    $(document).on("change", "#lifnr", function(e) {
        var perihal = $("input[name='perihal']").val();
        var plant = $("input[name='plant']").val();
        var lifnr = $("#lifnr").val();
		var id_kualifikasi = $("input[name='id_spesifikasi']").val();
        $("select[name='id_kualifikasi']").html('');
        $.ajax({
            url: baseURL + 'spk/spk/get2/kualifikasi_vendor',
            type: 'POST',
            dataType: 'JSON',
            data: {
                plant: plant,
                lifnr: lifnr
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    var list_id_kualifikasi = v.list_id_kualifikasi.slice(0, -1).split(",");
                    var list_kualifikasi = v.list_kualifikasi.slice(0, -1).split(",");
                    var array = [];
                    var output = '';
                    output += '<option value="">Pilih Kualifikasi</option>';
                    $.each(list_id_kualifikasi, function(x, y) {
						if(id_kualifikasi==list_id_kualifikasi[x]){
							output += '<option value="' + list_id_kualifikasi[x] + '" selected>' + list_kualifikasi[x] + '</option>';
						}else{
							output += '<option value="' + list_id_kualifikasi[x] + '">' + list_kualifikasi[x] + '</option>';
						}
                        
                    });
                    $("select[name='id_kualifikasi']").html(output);
                });
            }
        });
    });

    $(document).on('click', '.spk_drop', function() {
        var modal = $('#modal-spk_drop');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#nama_spk', modal).html($(this).attr('data-nama_spk'));
        $('#nomor_spk', modal).val($(this).attr('data-nomor_spk'));
        modal.modal('show');
    });
    $(document).on('click', '.spk_cancel', function() {
        var modal = $('#modal-spk_cancel'); 
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#nama_spk', modal).html($(this).attr('data-nama_spk'));
        $('#nomor_spk', modal).val($(this).attr('data-nomor_spk'));
        $('#status_akhir', modal).val($(this).attr('data-status_akhir'));
        modal.modal('show');
    });
	
    $(document).on("click", ".spk_cancel_old", function(e) {
        var id_spk = $(this).attr('data-id_spk');
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Data SPK dicancel, apakah proses akan dilanjutkan?",
            dangerMode: true,
            successCallback: function() {

                $.ajax({
                    url: baseURL + 'spk/save/cancelspk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_spk: id_spk
                    },
                    success: function(data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);

                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    complete: function() {
                        location.reload();
                    }
                });
            }
        });
        e.preventDefault();
        return false;
    });

    $('button[name="save_drop_spk"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-drop-spk', true);
        var form = $('#form-drop-spk:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0; 

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    // url: baseURL + 'spk/save/finaldraft',
                    url: baseURL + 'spk/save/dropspk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-cancel_spk:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });
 
    $('button[name="save_cancel_spk"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-cancel-spk', true);
        var form = $('#form-cancel-spk:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0; 

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);
               
                $.ajax({
					url: baseURL + 'spk/save/cancelspk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData, 
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
                        } else { 
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    complete: function() {
                        location.reload();
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    //filter data spk
    $('#filter-date input, select', 'form[name="filter-data-spk"]').on('change', function() {
        $('form[name="filter-data-spk"]').submit();
    });

    //auto complete dari sini 
    $("#lifnr").select2({
        dropdownParent: $('#form-spk'),
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        }, 
        ajax: {
            url: baseURL + 'spk/spk/get2/vendor',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { 
                    q: params.term, // search term
                    page: params.page,
                    plant: $("input[name='plant']").val(),
                    id_jenis_spk: $("select[name='id_jenis_spk']").val(),
                    id_jenis_vendor: $("select[name='id_jenis_vendor']").val()
                };
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: false
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            // var markup = '<div class="clearfix">' + repo.NAME1 + ' (' + repo.EKORG + ')</div>';
            var markup = '<div class="clearfix">' + repo.NAME1 + '</div>';
            return markup;
        },
        templateSelection: function(repo) {
            if (repo.LIFNR) {
                // return repo.NAME1 + ' (' + repo.EKORG + ')';
                return repo.NAME1;
            } else {
                return repo.text;
            }
        }
    });
    $("#lifnr").on('select2:select select2:unselecting change', function(e) {
        var nama_vendor = "";
        var LIFNR = "";
        var CITY1 = "";
        var STRAS = "";
        if (typeof e.params !== "undefined" && e.params.data) {
            nama_vendor = e.params.data.NAME1;
            LIFNR = e.params.data.LIFNR;
            CITY1 = e.params.data.CITY1;
            STRAS = e.params.data.STRAS;
        }
        $("input[name='nama_vendor']").val(nama_vendor);
        $("input[name='LIFNR']").val(LIFNR);
        $("input[name='CITY1']").val(CITY1);
        $("input[name='STRAS']").val(STRAS);
    });
    //auto complete sampe sini	

    // //filter pabrik
    // $.ajax({
    // url: baseURL+'spk/spk/get/plant',
    // type: 'POST',
    // dataType: 'JSON',
    // success: function(data){
    // var no = 0;
    // var list = '';
    // $.each(data, function(i,v){
    // list += '<option value="'+v.plant+'">'+v.plant+'</option>';
    // });
    // $('#filter_plant').html(list);
    // }
    // });


    $('#download-template').on('click', function() {
        var modal = $('#modal-download');
        $('#id_jenis_spk_d', modal).empty().trigger('change');
        $.ajax({
            url: baseURL + 'spk/get/jenisspk',
            type: 'POST',
            dataType: 'JSON',
            success: function(data) {
                if (data.sts == 'OK') {
                    var jenisSPK = [];
                    $.each(data.data, function(i, v) {
                        jenisSPK.push({
                            id: v.link,
                            text: v.jenis_spk
                        });
                    });

                    $('#id_jenis_spk_d', modal).select2({ data: jenisSPK });

                    $('#btn_download', modal).on('click', function() {
                        var win = window.open($('#id_jenis_spk_d', modal).val(), '_blank');
                        win.focus();
                    });
                    modal.modal('show');
                } else {
                    kiranaAlert(false, data.msg, 'error', 'no');
                }
            },
            error: function(data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });
    $(document).on('click', '.spk-komentar', function() {
        var modal = $('#modal-komentar');
        var jumlah_komentar = $(this).attr('data-jumlah_komentar');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $.ajax({
            url: baseURL + 'spk/get/komentar',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $(this).attr('data-id_spk'),
                jumlah_komentar: jumlah_komentar
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    refreshChats(data.data);
                    $('#title-spk').html(data.spk.jenis_spk + ", " + data.spk.nama_spk)
                    modal.modal('show');
                } else {
                    kiranaAlert(false, data.msg, 'error', 'no');
                }
            },
            error: function(data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('shown.bs.modal', '#modal-komentar', function() {
        lastElementTop = $('.direct-chat-messages').get(0).scrollHeight;
        scrollAmount = lastElementTop;

        $('.direct-chat-messages').animate({ scrollTop: scrollAmount }, 500);

    });

    function refreshChats(data) {
        $('.chats').remove();
        $.each(data, function(i, v) {
            // var komentar = (v.komentar).replace(' ', '<br>');
            // var komentar = v.komentar;
            // komentar	 = komentar.str.replace('/', '<br />');


            var template = $('.template-left').clone().removeClass('template-left hide').addClass('chats');
            if (v.me)
                template = $('.template-right').clone().removeClass('template-right hide').addClass('chats');

            $(template).find('.direct-chat-img').attr('src', v.gambar);
            $(template).find('.direct-chat-name').html(v.nama);
            $(template).find('.direct-chat-text').html(v.komentar);
            $(template).find('.direct-chat-timestamp').html(moment(v.tanggal_buat + " " + v.jam).format('DD.MM.YYYY HH:mm'));
            $('#chat-body').append(template).scrollTop($("#chat-body")[0].scrollHeight);

        });
        // $('input[name="komentar"]').val('');
        $('textarea[name="komentar"]').val('');
    }

    $('button[name="btn_komentar"]').on('click', function(e) {

        e.preventDefault();
        validate('#form-komentar', true);
        var form = $('#form-komentar:visible');
        var valid = form.valid();
        if (valid && $('#komentar').val().length > 0) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/komentar',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        $("input[name='isproses']").val(0);
                        if (data.sts == 'OK') {
                            refreshChats(data.data);
                        } else {
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $(document).on('click', '.spk-upload,.spk-edit-upload', function() {
        var modal = $('#modal-upload');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#id_upload', modal).val($(this).attr('data-id_upload'));
        if ($(this).attr('data-tipe') == 'template') {
            $('#id_oto', modal).val($(this).attr('data-id_oto_jenis'));
        } else {
            $('#id_oto', modal).val($(this).attr('data-id_oto_vendor'));
        }
        $('#tipe', modal).val($(this).attr('data-tipe'));
        modal.modal('show');
    });

    $(document).on('click', '.spk-final-draft', function() {
        var modal = $('#modal-final-draft');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#nama_spk', modal).html($(this).attr('data-nama_spk'));
        $('#nomor_spk', modal).val($(this).attr('data-nomor_spk'));
        modal.modal('show');
    });

    $(document).on('change', '#id_jenis_spk', function() {
        $('#id_nama_spk').empty().trigger('change');
        $("select[name='id_jenis_vendor']").val('').trigger("change.select2");
        $("select[name='LIFNR']").val('').trigger("change");
        $("input[name='LIFNR']").val('');
        $("input[name='CITY1']").val('');
        $("input[name='STRAS']").val('');
		$("select[name='id_kualifikasi']").val('').trigger("change.select2");
		$('#lifnr').val('').trigger('change');



        $.ajax({
            url: baseURL + 'spk/get/namaspk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: $(this).val()
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    var aNamaSPK = [];
                    $.each(data.data, function(i, v) {
                        var selected = false;
                        if ($(this).attr('data-selected') == v.id_nama_spk)
                            selected = true;
                        aNamaSPK.push({
                            id: v.id_nama_spk,
                            text: v.nama_spk,
                            selected: selected
                        });
                    });
                    $('#id_nama_spk').select2({ data: aNamaSPK });
                } else {
                    $("input[name='isproses']").val(0);
                    kiranaAlert(false, data.msg, 'error', 'no');
                }
            },
            error: function(data) {
                $("input[name='isproses']").val(0);
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });
    $(document).on('change', '#id_jenis_vendor', function() {
		$("select[name='id_kualifikasi']").val('').trigger("change.select2");
        $("select[name='LIFNR']").val('').trigger("change");
        $("input[name='LIFNR']").val('');
        $("input[name='CITY1']").val('');
        $("input[name='STRAS']").val('');
		$('#lifnr').val('').trigger('change');
    });


    $('#add-spk').on('click', function() {
        var modal = $('#modal-spk');
        $('#title', modal).html('Tambah');
        $('#tanggal_perjanjian,#tanggal_berlaku_spk,#tanggal_berakhir_spk').datepicker('setDate', moment().toDate());
        modal.modal('show');
    });

    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $('button[name="save_upload"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-upload', true);
        var form = $('#form-upload:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/dokumen',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $("input[name='isproses']").val(0);
                                $('#modal-upload').modal('hide');
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $('button[name="save_final_draft"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-final-draft', true);
        var form = $('#form-final-draft:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/finaldraft',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-final-draft:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $('button[name="save_final"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-final', true);
        var form = $('#form-final:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/final',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-final:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $('button[name="save_spk"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-spk', true);
        var form = $('#form-spk:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/spk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-spk:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $(document).on('click', 'button.approval', function(e) {
        e.preventDefault();
        var modal = $('#modal-review');
        var action = $(this, modal).attr('data-action');
        var id = $('#id_spk', modal).val();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            $.ajax({
                url: baseURL + 'spk/save/reviewspk',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    action: action
                },
                success: function(data) {
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function() {
                            $('.modal-review:visible').modal('hide');
                            location.reload();
                        });
                    } else {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, data.msg, 'error', 'no');
                    }
                },
                error: function(data) {
                    $("input[name='isproses']").val(0);
                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                }
            });
        } else {
            swal({
                title: "Silahkan tunggu sampai proses selesai.",
                icon: 'info'
            });
        }
        return false;
    });

    $(document).on('click', '.spk-edit', function(e) {
        var id = $(this).attr('data-id_spk');
        var modal = $('#modal-spk');
        $.ajax({
            url: baseURL + 'spk/get/spk', 
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id 
            },
            success: function(data) {
                validate('#form-spk', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_spk', modal).val(dataEdit.id_spk);
                    $('#id_jenis_spk', modal).val(dataEdit.id_jenis_spk).trigger('change');
                    $('#id_nama_spk', modal).attr('data-selected', dataEdit.id_nama_spk);
                    $('#id_nama_spk', modal).val(dataEdit.id_nama_spk).trigger('change');
                    $('#id_jenis_vendor', modal).val(dataEdit.id_jenis_vendor).trigger('change');
                    $('#perihal', modal).val(dataEdit.perihal);
                    $('#tanggal_perjanjian', modal).datepicker('setDate', moment(dataEdit.tanggal_perjanjian).toDate());
                    $('#tanggal_berlaku_spk', modal).datepicker('setDate', moment(dataEdit.tanggal_berlaku_spk).toDate());
                    $('#tanggal_berakhir_spk', modal).datepicker('setDate', moment(dataEdit.tanggal_berakhir_spk).toDate());
                    $('#SPPKP', modal).val(dataEdit.SPPKP);
                    $('#id_spesifikasi', modal).val(dataEdit.id_kualifikasi);
					var control = $('#lifnr').empty().data('select2');
                    var adapter = control.dataAdapter;
                    var nama = dataEdit.nama_vendor + ' (' + dataEdit.plant + ')';
                    // console.log(nama);
                    adapter.addOptions(adapter.convertToOptions([{ "id": dataEdit.lifnr, "text": nama }]));
                    $('#lifnr').trigger('change'); 

                    // $('#id_kualifikasi', modal).val(dataEdit.id_kualifikasi).trigger('change');
					$('#nama_vendor', modal).val(dataEdit.nama_vendor);
                    $('#LIFNR', modal).val(dataEdit.lifnr);
                    $('#CITY1', modal).val(dataEdit.CITY1);
                    $('#STRAS', modal).val(dataEdit.STRAS);

                    $('#title', modal).html("Edit");
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on('click', '.spk-review', function(e) {
        var id = $(this).attr('data-id_spk');
        var modal = $('#modal-review');
        $.ajax({
            url: baseURL + 'spk/get/spk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(data) {
                validate('#form-review', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_spk', modal).val(dataEdit.id_spk);
                    $('#plant', modal).html(dataEdit.plant);
                    $('#jenis_spk', modal).html(dataEdit.jenis_spk);
                    $('#nama_spk', modal).html(dataEdit.nama_spk);
                    $('#jenis_vendor', modal).html(dataEdit.jenis_vendor);
                    $('#tanggal_perjanjian', modal).html(moment(dataEdit.tanggal_perjanjian).format('DD.MM.YYYY'));
                    $('#tanggal_berlaku_spk', modal).html(moment(dataEdit.tanggal_berlaku_spk).format('DD.MM.YYYY'));
                    $('#tanggal_berakhir_spk', modal).html(moment(dataEdit.tanggal_berakhir_spk).format('DD.MM.YYYY'));
                    $('#SPPKP', modal).html(dataEdit.SPPKP);
                    $('#nomor_spk', modal).val(dataEdit.nomor_spk);
                    if (dataEdit.id_status == 3)
                        $('#buttons', modal).addClass('hide');
                    else
                        $('#buttons', modal).removeClass('hide');
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on('click', '.spk-final-spk', function(e) {
        var id = $(this).attr('data-id_spk');
        var modal = $('#modal-final');
        $.ajax({
            url: baseURL + 'spk/get/spk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(data) {
                validate('#form-final', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_spk', modal).val(dataEdit.id_spk);
                    $('#plant', modal).html(dataEdit.plant);
                    $('#jenis_spk', modal).html(dataEdit.jenis_spk);
                    $('#nama_spk', modal).html(dataEdit.nama_spk);
                    $('#jenis_vendor', modal).html(dataEdit.jenis_vendor);
                    $('#tanggal_perjanjian', modal).html(moment(dataEdit.tanggal_perjanjian).format('DD.MM.YYYY'));
                    $('#tanggal_berlaku_spk', modal).html(moment(dataEdit.tanggal_berlaku_spk).format('DD.MM.YYYY'));
                    $('#tanggal_berakhir_spk', modal).html(moment(dataEdit.tanggal_berakhir_spk).format('DD.MM.YYYY'));
                    $('#SPPKP', modal).html(dataEdit.SPPKP);
                    $('#nomor_spk', modal).html(dataEdit.nomor_spk);
                    $('#tanggal_kirim', modal).datepicker('setDate', moment().toDate());
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on("click", ".spk-delete", function(e) {
        var id = $(this).attr("data-id_spk");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda akan menghapus data?",
            dangerMode: true,
            successCallback: function() {
                $.ajax({
                    url: baseURL + 'spk/delete/spk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            }
        });
    });

    $(document).on("click", ".spk-submit", function(e) {
        var id = $(this).attr("data-id_spk");
        $.ajax({
            url: baseURL + 'spk/get/submitspk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaConfirm({
                        title: "Konfirmasi",
                        text: "Apakah anda yakin untuk melakukan submit SPK ini?",
                        dangerMode: true,
                        successCallback: function() {
                            $.ajax({
                                url: baseURL + 'spk/save/submitspk',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    if (data.sts == 'OK') {
                                        kiranaAlert(data.sts, data.msg);
                                    } else {
                                        kiranaAlert(data.sts, data.msg, 'error', 'no');
                                    }
                                },
                                error: function(data) {
                                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                                }
                            });
                        }
                    });
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function(data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on("click", ".spk-assign", function(e) {
        var id = $(this).attr("data-id_spk");
        $.ajax({
            url: baseURL + 'spk/get/submitspk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaConfirm({
                        title: "Konfirmasi",
                        text: "Apakah anda yakin untuk melakukan assign Perijinan untuk divisi terkait?",
                        dangerMode: true,
                        successCallback: function() {
                            $.ajax({
                                url: baseURL + 'spk/save/assignspk',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    if (data.sts == 'OK') {
                                        kiranaAlert(data.sts, data.msg);
                                    } else {
                                        kiranaAlert(data.sts, data.msg, 'error', 'no');
                                    }
                                },
                                error: function(data) {
                                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                                }
                            });
                        }
                    });
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function(data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on("click", ".spk-approve", function(e) {
        var id = $(this).attr("data-id_spk");
        $.ajax({
            url: baseURL + 'spk/get/approvespk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaConfirm({
                        title: "Konfirmasi",
                        text: data.msg,
                        dangerMode: true,
                        successCallback: function() {
                            $.ajax({
                                url: baseURL + 'spk/save/approvespk',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    id: id
                                },
                                success: function(data) {
                                    if (data.sts == 'OK') {
                                        kiranaAlert(data.sts, data.msg);
                                    } else {
                                        kiranaAlert(data.sts, data.msg, 'error', 'no');
                                    }
                                },
                                error: function(data) {
                                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                                }
                            });
                        }
                    });
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function(data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.spk-attachments', function() {
        var tipe = $(this).attr('data-tipe');
        var id = $(this).attr('data-id_spk');
        var id_jenis_spk = $(this).attr('data-id_jenis_spk');
        loadAttachments(id, tipe, true);
    });

    function loadAttachments(id, tipe, showModal = false) {
		if(tipe=='vendor_dokumen'){
			var caption_tipe = 'vendor';
		}else if(tipe=='vendor_kualifikasi'){
			var caption_tipe = 'kualifikasi';
		}else{
			var caption_tipe = tipe;
		}
        $('#modal-attachments').find('#title').html(caption_tipe);
        // $('#modal-attachments').find('#container-attachments').html(null)
        $.ajax({
            url: baseURL + 'spk/get/attachments',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                tipe: tipe
            },
            success: function(data) {
                if (data.data) {
                    var modal = $('#modal-attachments');
                    modal.find('#container-attachments').html(data.data);
                    if (showModal) {
                        modal.modal('show');
                    }
                    reinitFancybox();
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    }

    $(document).on('hidden.bs.modal', '#modal-upload', function() {
        var tipe = $('#tipe', $(this)).val();
        var id = $('#id_spk', $(this)).val();
        loadAttachments(id, tipe);
        $('#id_spk', $(this)).val(null);
        $('#id_oto', $(this)).val(null);
        $('#id_upload', $(this)).val(null);
        $('#tipe', $(this)).val(null);
        $('input[name="dokumen"]', $(this)).val(null);
    });

    $('#modal-spk').on('hide.bs.modal', function() {
        $('#id_spk', $(this)).val(null);
        // validator.resetForm();
    });

    $('#modal-final-draft').on('hide.bs.modal', function() {
        $('#id_spk', $(this)).val(null);
        // validator.resetForm();
    });

    $('#modal-komentar').on('hide.bs.modal', function() {
        $('#id_spk', $(this)).val(null);
        // validator.resetForm();
    });

    $('#modal-download').on('hide.bs.modal', function() {
        $('#btn_download').off('click');
    });
});
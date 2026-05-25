<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Additional styles */
        .content {
            margin-left: 0; /* Akan diatur otomatis oleh JS */
            padding: 20px;
        }

        .sidebar {
            min-width: 0;
            width: max-content;
            max-width: 100vw;
            background-color: #fff;
            padding: 20px 24px 20px 24px;
            z-index: 100;
            height: 100vh; /* Tambahkan ini agar sidebar penuh ke bawah */
            position: fixed; /* Agar sidebar tetap di posisi kiri */
            top: 0;
            left: 0;
        }

        .sidebar-right-shadow {
            box-shadow: 1px 0px 8px rgba(0, 0, 0, 0.1); /* Bayangan di sisi kanan sidebar */
        }

        /* Tambahan untuk footer agar mulai dari setelah sidebar */
        .footer {
            background-color: #EAEAEA;
            color: #636363;
            text-align: center;
            font-size: 12px;
            padding: 5px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            /* Lebar footer menyesuaikan sisa layar di kanan sidebar */
            width: calc(100% - var(--sidebar-width, 240px));
            margin-left: var(--sidebar-width, 240px);
            z-index: 101;
        }

        /* NAV LINK */
        .nav-link {
            display: flex;
            align-items: center;
        }

        .nav-link .icon {
            margin-right: 5px;
        }

        .sidebar .nav-link.active-sidebar {
            background-color: #0077ff !important;
            color: #fff !important;
            font-weight: 500 !important;   /* Tambahkan ini */
            transition: background-color 0.3s;
        }
        .sidebar .nav-link.active-sidebar i {
            color: #fff !important;
            transition: color 0.3s;
        }

        .custom-button {
            color: #A0A0A0;
            /* Warna teks saat tombol normal */
            transition: background-color 0.3s, color 0.3s;
            /* Efek transisi ketika hover */
            /* outline: none; */
        }

        .custom-button:hover {
            background-color: #007BFF;
            /* Warna latar belakang saat tombol dihover */
            color: white;
            /* Warna teks saat tombol dihover menjadi putih */
        }

        .custom-card {
            padding: 30px;
            width: 395px;
            height: 280px;
            background-color: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .circle-image {
            width: 79px;
            height: 79px;
            border-radius: 50%;
        }

        .custom-title {
            font-weight: 600;
            font-size: 25px;
            color: #252525;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .custom-subtitle {
            font-weight: 400;
            font-size: 20px;
            color: #898989;
            font-family: 'Poppins', sans-serif;
            margin-top: 10px;
        }

        .custom-button {
            width: 335px;
            height: 43px;
            border-radius: 10px;
            background-color: #EAEAEA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            outline: none;
        }

        .custom-button-detail {
            width: 180px;
            height: 45px;
            border-radius: 10px;
            background-color: #EAEAEA;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            margin-left: auto;
            color: #A0A0A0;
            /* Warna teks saat tombol normal */
            transition: background-color 0.3s, color 0.3s;
            /* Efek transisi ketika hover */
        }

        .custom-button-detail:hover {
            background-color: #007BFF;
            /* Warna latar belakang saat tombol dihover */
            color: white;
            /* Warna teks saat tombol dihover menjadi putih */
        }

        .button-text {
            font-weight: 500;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            margin-left: 10px;
            margin-right: 10px;
            text-decoration: none;
            color: #A0A0A0;
        }

        .button-text:hover {
            text-decoration: none;
            color: #fff;
        }

        .text {
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
        }

        /* DROPDOWN */
        .dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
    
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
            transition: 0.3s;
            opacity: 0;
            transform: translateY(-10px);
        }
    
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
        }
    
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
    
        .dropdown:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
    
        .dropdown.active .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        /* DROPDOWN END*/

        .btn-info {
            color: #fff !important; /* Warna teks putih */
            background-color: #0079FF !important; /* Warna latar belakang biru */
            border-color: #0079FF !important; /* Warna border biru */
        }

        .btn-info:hover {
            color: #fff !important; /* Warna teks tetap putih saat hover */
            background-color: #0056b3 !important; /* Warna latar belakang lebih gelap saat hover */
            border-color: #004a9c !important; /* Warna border lebih gelap saat hover */
        }

        .btn-info:focus, .btn-info.focus {
            color: #fff !important; /* Warna teks tetap putih saat fokus */
            background-color: #0056b3 !important; /* Warna latar belakang lebih gelap saat fokus */
            border-color: #004a9c !important; /* Warna border lebih gelap saat fokus */
            box-shadow: 0 0 0 0.2rem rgba(0, 121, 255, 0.5) !important; /* Efek fokus */
        }

        .btn-info.disabled, .btn-info:disabled {
            color: #fff !important; /* Warna teks tetap putih saat disabled */
            background-color: #0079FF !important; /* Warna latar belakang */
            border-color: #0079FF !important; /* Warna border */
        }

        .btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,
        .show > .btn-info.dropdown-toggle {
            color: #fff !important; /* Warna teks tetap putih saat aktif */
            background-color: #004a9c !important; /* Warna latar belakang lebih gelap saat aktif */
            border-color: #004080 !important; /* Warna border lebih gelap saat aktif */
        }

        /* button minus subtopics */
        .btn-minus-red {
            background-color: #ffeaea !important;
            border: 1.5px solid #ff3b3b !important;
            border-radius: 8px !important;
            color: #ff3b3b !important;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            transition: background 0.5s, color 0.5s;
            font-size: 20px;
            font-weight: bold;
            padding: 0;
        }
        .btn-minus-red:hover {
            background-color: #ff3b3b !important;
            color: #fff !important;
            border-color: #ff3b3b !important;
        }
        .btn-minus-red .minus-sign {
            color: #ff3b3b;
            font-size: 22px;
            font-weight: bold;
            margin: 0 auto;
            line-height: 1;
            transition: color 0.5s;
        }
        .btn-minus-red:hover .minus-sign {
            color: #fff;
        }

        /* Button Add subtopics */
        /* Efek hover untuk tombol Add Sub-Topics */
        .btn-add-subtopic-hover:hover,
        #add-edit-subtopic-btn:hover {
            background-color: #258eff !important;
            color: #fff !important;
            border-color: #258eff !important;
            transition: background 0.5s, color 0.5s;
        }

        /* Custom style for file input button */
        .custom-file-group {
            display: flex;
            align-items: center;
            gap: 0;
        }
        .custom-file-label {
            flex: 1;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            margin-right: -1px; /* hilangkan double border */
        }
        .custom-file-btn {
            background: #eaf4ff;
            color: #258eff;
            border: 1.5px solid #258eff;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 0.5rem !important;
            border-bottom-right-radius: 0.5rem !important;
            padding: 6px 18px;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            outline: none;
            white-space: nowrap;
            height: 100%;
            display: flex;
            align-items: center;
        }
        .custom-file-btn:hover {
            background: #258eff;
            color: #fff;
            border-color: #258eff;
        }
        .custom-file-input {
            display: none;
        }
    </style>

    <title>Database Management System with MySQL</title>
    <link rel="icon" href={{asset("./images/logo.png")}} type="image/png">

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <!-- JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <!-- Place these in the <head> section -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script>
        
        // Define the font files in base64
        var robotoRegularBase64 = 'BASE64_STRING_OF_ROBOTO_REGULAR';
        var robotoBoldBase64 = 'BASE64_STRING_OF_ROBOTO_BOLD';
        var robotoItalicBase64 = 'BASE64_STRING_OF_ROBOTO_ITALIC';
        var robotoBoldItalicBase64 = 'BASE64_STRING_OF_ROBOTO_BOLDITALIC';
        
        // Prepare the virtual file system object
        var vfs = {
          "Roboto-Regular.ttf": robotoRegularBase64,
          "Roboto-Bold.ttf": robotoBoldBase64,
          "Roboto-Italic.ttf": robotoItalicBase64,
          "Roboto-BoldItalic.ttf": robotoBoldItalicBase64
        };
    </script>
    <script>
        // Script ini akan mengatur margin kiri (.content) dan lebar footer secara otomatis
        // sesuai lebar sidebar, sehingga tabel dan footer tidak tertumpuk sidebar.
        function adjustContentMargin() {
            var sidebar = document.querySelector('.sidebar');
            var content = document.querySelector('.content');
            var footer = document.querySelector('.footer');
            if (sidebar && content) {
                content.style.marginLeft = sidebar.offsetWidth + 'px';
            }
            if (sidebar && footer) {
                // Set CSS variable untuk lebar sidebar
                document.documentElement.style.setProperty('--sidebar-width', sidebar.offsetWidth + 'px');
            }
        }
        window.addEventListener('DOMContentLoaded', adjustContentMargin);
        window.addEventListener('resize', adjustContentMargin);
    </script>
    <script>
        // Ketika menu Topics diklik, load table topik via AJAX tanpa reload halaman
        $(document).on('click', '#show-topics-table', function(e) {
            e.preventDefault();
            $.get("{{ route('v2.teacher.topics.table') }}", function(data) {
                $('#main-table-content').html(data);
                adjustContentMargin();
            });
        });
    </script>
    <script>
        // Ketika menu sidebar diklik, beri highlight hanya pada menu yang aktif
        $(document).on('click', '.sidebar .nav-link', function() {
            $('.sidebar .nav-link').removeClass('active-sidebar');
            $(this).addClass('active-sidebar');
        });

        // AJAX untuk Topics (tetap seperti sebelumnya)
        $(document).on('click', '#show-topics-table', function(e) {
            e.preventDefault();
            $.get("{{ route('v2.teacher.topics.table') }}", function(data) {
                $('#main-table-content').html(data);
                adjustContentMargin();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Saat halaman pertama kali dibuka, langsung load tabel topics
            $.get("{{ route('v2.teacher.topics.table') }}", function(data) {
                $('#main-table-content').html(data);
            });
        });
    </script>

    {{-- Tampilkan nama file baru saat dipilih di Modal Edit --}}
    <script>
        $(document).on('change', '#edit-modul-input', function() {
            $('#edit-modul-filename').val(this.files.length ? this.files[0].name : '');
        });
    </script>

    {{-- TOPICS MANAGEMENT --}}
    <script>
        // Add Topics
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'add-subtopic-btn') {
                const container = document.getElementById('subtopics-container');
                if (container) {
                    const newGroup = document.createElement('div');
                    newGroup.className = 'row mt-3 mb-3 subtopic-group align-items-center';
                    newGroup.style.borderTop = '1px solid #ccc';
                    newGroup.innerHTML = `
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Sub-Topic</label>
                                <input type="text" class="form-control" name="sub_topic_title[]" autocomplete="off" required>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label fw-semibold">Upload Module</label>
                                <div class="custom-file-group">
                                    <input type="text" class="form-control custom-file-label" id="modul-filename" placeholder="No file chosen" readonly>
                                    <label class="custom-file-btn mb-0">
                                        Choose File
                                        <input type="file" class="custom-file-input" name="sub_topic_file[]" id="modul-input" accept=".pdf">
                                    </label>
                                </div>
                                <div class="text-danger" style="font-size: 12px; margin-left: 0.2rem;">Please upload a file with .pdf extension.</div>
                            </div>
                            <div class="col-md-1 d-flex justify-content-center align-items-center" style="height: 100%; margin-top: 2rem;">
                                <button type="button" class="btn btn-minus-red remove-subtopic-btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px;">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                    `;
                    container.appendChild(newGroup);
        
                    // Event untuk menampilkan nama file yang dipilih pada placeholder kolom Upload Module
                    const fileInput = newGroup.querySelector('.custom-file-input');
                    const fileLabel = newGroup.querySelector('.custom-file-label');
                    fileInput.addEventListener('change', function() {
                        fileLabel.value = this.files.length ? this.files[0].name : '';
                    });
                }
            }
        });
        
        // Remove subtopic (add modal)
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.classList.contains('remove-subtopic-btn') || e.target.closest('.remove-subtopic-btn'))) {
                const btn = e.target.closest('.remove-subtopic-btn');
                btn.closest('.subtopic-group').remove();
            }
        });
    </script>
    {{-- Menampilkan nama file yang dipilih pada kolom Upload Module di Add Modal --}}
    <script>
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('custom-file-input')) {
                // Cari input text (label) di parent yang sama
                const fileInput = e.target;
                const fileLabel = fileInput.closest('.custom-file-group').querySelector('.custom-file-label');
                if (fileLabel) {
                    fileLabel.value = fileInput.files.length ? fileInput.files[0].name : '';
                }
            }
        });
    </script>

    {{-- Script untuk edit topik dan sub-topik pada modal --}}
    <script>
        function editTopic(topicId) {
            // Ambil data topic & subtopic via AJAX
            $.get('/select/teacher/topics/' + topicId + '/edit', function(data) {
                $('#edit_topic_id').val(data.topic.id);
                $('#edit_topic_title').val(data.topic.title);

                // Set timer (convert detik ke menit)
                if (data.topic.countdown_seconds !== undefined && data.topic.countdown_seconds !== null) {
                    $('#edit_countdown_minutes').val(Math.floor(data.topic.countdown_seconds / 60));
                } else {
                    $('#edit_countdown_minutes').val('');
                }

                // Tampilkan nama file schema jika ada
                if (data.topic.schema_file_name) {
                    $('#edit_schema_filename').val(data.topic.schema_file_name);
                } else {
                    $('#edit_schema_filename').val('No file chosen');
                }
                $('#edit_schema_file').val('');

                // Saat user pilih file baru, update kolom nama file
                $('#edit_schema_file').off('change').on('change', function() {
                    $('#edit_schema_filename').val(this.files.length ? this.files[0].name : 'No file chosen');
                });

                // Render subtopics
                let subtopicsHtml = '';
                data.subtopics.forEach(function(sub, idx) {
                    subtopicsHtml += `
                        <div class="row mb-3 edit-subtopic-group align-items-center" style="border-top: 1px solid #ccc">
                            <div class="col-md-7">
                                <label class="form-label fw-bold">Sub-Topic</label>
                                <input type="hidden" name="sub_topic_ids[]" value="${sub.id}">
                                <input type="text" class="form-control" name="sub_topic_titles[]" value="${sub.title}" required>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label fw-semibold">Upload Module</label>
                                <div class="custom-file-group">
                                    <input type="text" class="form-control custom-file-label" value="${sub.file_name ?? ''}" placeholder="No file chosen" readonly>
                                    <label class="custom-file-btn mb-0">
                                        Choose File
                                        <input type="file" class="custom-file-input" name="edit_sub_topic_file[]" accept=".pdf">
                                    </label>
                                </div>
                                <div class="text-danger" style="font-size: 12px; margin-left: 0.2rem;">Please upload a file with .pdf extension.</div>
                            </div>
                            <div class="col-md-1 d-flex justify-content-center align-items-center" style="height: 100%; margin-top: 2rem;">
                                <button type="button" class="btn btn-minus-red remove-subtopic-btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px;">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                $('#edit-subtopics-container').html(subtopicsHtml);

                // Setelah menerima data dari AJAX
                if (data.topic.is_sequential == 1) {
                    $('#edit_isSequentialSwitch').prop('checked', true);
                } else {
                    $('#edit_isSequentialSwitch').prop('checked', false);
                }
        
                // Tampilkan modal
                var editModal = new bootstrap.Modal(document.getElementById('editTopicModal'));
                editModal.show();
            });
        }

        // Tampilkan nama file baru saat dipilih
        $(document).on('change', '#edit_schema_file', function() {
            $('#edit_schema_filename').val(this.files.length ? this.files[0].name : '');
        });
        
        // Tambah subtopic baru di modal edit
        $(document).on('click', '#add-edit-subtopic-btn', function() {
            $('#edit-subtopics-container').append(`
                <div class="row mb-3 edit-subtopic-group align-items-center" style="border-top: 1px solid #ccc">
                    <div class="col-md-7">
                        <label class="form-label fw-bold">Sub-Topic</label>
                        <input type="hidden" name="sub_topic_ids[]" value="">
                        <input type="text" class="form-control" name="sub_topic_titles[]" required placeholder="Other Sub-Topics">
                    </div>
                    <div class="col-md-4 mt-3">
                        <label class="form-label fw-semibold">Upload Module</label>
                        <div class="custom-file-group">
                            <input type="text" class="form-control custom-file-label" placeholder="No file chosen" readonly>
                            <label class="custom-file-btn mb-0">
                                Choose File
                                <input type="file" class="custom-file-input" name="edit_sub_topic_file[]" accept=".pdf">
                            </label>
                        </div>
                        <div class="text-danger" style="font-size: 12px; margin-left: 0.2rem;">Please upload a file with .pdf extension.</div>
                    </div>
                    <div class="col-md-1 d-flex justify-content-center align-items-center" style="height: 100%; margin-top: 2rem;">
                        <button type="button" class="btn btn-minus-red remove-edit-subtopic-btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px;">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            `);
        });
        
        // Hapus subtopic di modal edit
        // $(document).on('click', '.remove-edit-subtopic-btn', function() {
        //     $(this).closest('.edit-subtopic-group').remove();
        // });
        $(document).on('click', '.remove-edit-subtopic-btn', function() {
            var $row = $(this).closest('.edit-subtopic-group');
            var subtopicId = $row.find('input[name="sub_topic_ids[]"]').val();

            if (subtopicId) {
                if (confirm('Hapus subtopik ini beserta file-nya?')) {
                    $.ajax({
                        url: '/select/teacher/subtopics/' + subtopicId + '/delete',
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            $row.remove();
                        },
                        error: function() {
                            alert('Gagal menghapus subtopik.');
                        }
                    });
                }
            } else {
                // Jika subtopic baru (belum ada di DB), cukup hapus dari DOM
                $row.remove();
            }
        });

        // Jika ada kemungkinan class-nya .remove-subtopic-btn (untuk konsistensi)
        $(document).on('click', '.remove-subtopic-btn', function() {
            $(this).closest('.edit-subtopic-group').remove();
        });
        
        // Submit edit topic
        $(document).on('submit', '#edit-topic-form', function(e) {
            e.preventDefault();
            var topicId = $('#edit_topic_id').val();
            var formData = new FormData(this);
            formData.append('_method', 'PUT');
            $.ajax({
                url: '/select/teacher/topics/' + topicId,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#editTopicModal').modal('hide');
                    $.get("{{ route('v2.teacher.topics.table') }}", function(data) {
                        $('#main-table-content').html(data);
                    });
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        if (errors.question) msg += errors.question.join('<br>');
                        // Tambahkan error lain jika perlu
                        alert(msg || 'Gagal menyimpan data. Pastikan semua field terisi dengan benar.');
                    } else {
                        alert('Gagal menyimpan data. Pastikan semua field terisi dengan benar.');
                    }
                }
            });
        });
    </script>

    {{-- Add topics tanpa reload page --}}
    <script>
        $(document).on('submit', 'form[action="{{ route('v2.teacher.topics.add') }}"]', function(e) {
            e.preventDefault();
            var $form = $(this)[0];
            var formData = new FormData($form);

            $.ajax({
                url: $form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#addTopicModal').modal('hide');
                    // Reload tabel topics
                    $.get("{{ route('v2.teacher.topics.table') }}", function(data) {
                        $('#main-table-content').html(data);
                    });
                    // Reset form dan subtopics
                    $form.reset();
                    $('#subtopics-container').html(`
                        <div class="row mb-3 subtopic-group align-items-center" style="border-top: 1px solid #ccc">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Sub-Topic</label>
                                <input type="text" class="form-control" name="sub_topic_title[]" autocomplete="off" required>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label fw-semibold">Upload Module</label>
                                <div class="custom-file-group">
                                    <input type="text" class="form-control custom-file-label" placeholder="No file chosen" readonly>
                                    <label class="custom-file-btn mb-0">
                                        Choose File
                                        <input type="file" class="custom-file-input" name="sub_topic_file[]" accept=".pdf">
                                    </label>
                                </div>
                                <div class="text-danger" style="font-size: 12px; margin-left: 0.2rem;">Please upload a file with .pdf extension.</div>
                            </div>
                        </div>
                    `);
                },
                error: function(xhr) {
                    alert('Gagal menambah topic. Pastikan semua field terisi dengan benar.');
                }
            });
        });
    </script>

    {{-- delete topik dan subtopik tanpa reload page --}}
    <script>
        $(document).on('click', '.delete-topic-btn', function() {
            var id = $(this).data('id');
            if (confirm('Delete this topic along with all sub-topics?')) {
                $.ajax({
                    url: '/select/teacher/topics/' + id + '/delete',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(res) {
                        // Reload tabel topics
                        $.get("{{ route('v2.teacher.topics.table') }}", function(data) {
                            $('#main-table-content').html(data);
                        });
                    },
                    error: function(xhr) {
                        alert('Gagal menghapus topic.');
                    }
                });
            }
        });
    </script>

    {{--  Update Preview Saat Subtopic Diganti pada Modal Add --}}
    <script>
        $(document).on('change', '#add_topic_detail_id', function() {
            var subtopicId = $(this).val();
            var $btn = $('#add-open-pdf-btn');
            var $msg = $('#add-modul-preview-message');
            var $file = $('#add-modul-preview-file');

            if (!subtopicId) {
                $btn.addClass('disabled').attr('href', '#').attr('tabindex', '-1').attr('aria-disabled', 'true').css('pointer-events', 'none');
                $msg.html('Pilih subtopik untuk melihat modul.');
                $file.html('');
                return;
            }
            $.get('/select/teacher/subtopics/' + subtopicId + '/modul', function(res) {
                if (res.file_name && res.file_path) {
                    var fileUrl = '{{ asset('') }}' + res.file_path + res.file_name;
                    $btn.removeClass('disabled').attr('href', fileUrl).attr('tabindex', '0').attr('aria-disabled', 'false').css('pointer-events', 'auto');
                    $msg.html('');
                    $file.html(`<iframe src="${fileUrl}" width="100%" height="400px" style="border:1px solid #ccc;"></iframe>`);
                } else {
                    $btn.addClass('disabled').attr('href', '#').attr('tabindex', '-1').attr('aria-disabled', 'true').css('pointer-events', 'none');
                    $msg.html('No module uploaded yet. Please upload a PDF module to preview.');
                    $file.html('');
                }
            });
        });

        // Saat modal dibuka, reset preview
        $('#addQuestionModal').on('shown.bs.modal', function () {
            $('#add-open-pdf-btn').addClass('disabled').attr('href', '#').attr('tabindex', '-1').attr('aria-disabled', 'true').css('pointer-events', 'none');
            $('#add-modul-preview-message').html('Pilih subtopik untuk melihat modul.');
            $('#add-modul-preview-file').html('');
        });
    </script>
    {{----------------------------------------------------------------------------------------------}}

    {{-- AJAX untuk Student Submissions --}}
    <script>
        $(document).on('click', 'a[href="/select/teacher/submissions"]', function(e) {
            e.preventDefault();
            $.get("/select/teacher/submissions", function(data) {
                $('#main-table-content').html(data);
                adjustContentMargin();
            });
        });
    </script>
    {{----------------------------------------------------------------------------------------------}}

    {{-- Questions Management --}}
    <script>
        // Ketika menu Questions diklik, load table Questions via AJAX tanpa reload halaman
        $(document).on('click', '#show-questions-management', function(e) {
            e.preventDefault();
            $.get("{{ route('v2.teacher.questions.table') }}", function(data) {
                $('#main-table-content').html(data);
                adjustContentMargin();
                // Setelah konten dimuat, ambil ulang data terbaru dari backend
                if (typeof reloadAllQuestionsData === 'function') {
                    reloadAllQuestionsData(function() {
                        if (typeof initQuestionsPage === 'function') {
                            initQuestionsPage();
                        }
                    });
                } else if (typeof initQuestionsPage === 'function') {
                    initQuestionsPage();
                }
            });
        });
    </script>
    {{----------------------------------------------------------------------------------------------}}
    
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg" style="background-color: #FEFEFE;">
        <div class="container-fluid">
            <img src={{asset("./images/logo.png")}} alt="logo" width="104" height="65">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="mx-auto">
                    <ul class="navbar-nav mb-2 mb-lg-0 justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/dashboard_teacher">Dashboard Teacher</a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <span>/</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/select/teacher/materials">Database Management System with MySQL</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown">
                    <p style="margin-top: 10px; margin-right: 10px;">{{auth()->user()->name}}
                        <img src="{{ asset('./images/Group.png') }}" alt="Group" style="height: 50px; margin-right: 10px;">
                        <i class="fas fa-chevron-down" style="color: #0079FF;"></i>
                    <div class="dropdown-content" id="dropdownContent">
                        <form id="logout-form" action="{{ route('logoutt') }}" method="POST">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </form>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </nav>
    <!-- ------------------------------------------------------------------------------------------ -->

    <div class="container-fluid">
        <div class="row">
            <!-- SIDEBAR -->
            <div class="col-auto sidebar sidebar-right-shadow" style="padding: 20px 0px;">
                <div class="d-flex justify-content-center mb-5" style="cursor: pointer;" onclick="window.location.href='/dashboard_teacher'">
                    <img src={{asset("./images/logo.png")}} alt="logo" width="104" height="65">
                </div>
                <div class="sidebar-sticky" style="margin-top: 20px;">
                    <ul class="nav flex-column">
                        <li class="nav-item" style="margin-bottom: 40px;">
                            <div class="row align-items-start">
                                <div class="col">
                                    <p style="font-weight: 600; font-size: 14px; color: #34364A; margin-left: 15px;">STUDENT WEBAPPS</p>
                                </div>
                                <div class="col d-flex justify-content-center">
                                    <img src="{{asset('./images/mysql/mysql-logo.png')}}" alt="learning-logo" style="height: 45px;">
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center active-sidebar"
                               href="#"
                               id="show-topics-table"
                               style="color: #34364A; white-space: nowrap; font-size: 16px;">
                                <i class="fas fa-book" style="margin-right: 12px;"></i>
                                Topics Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center"
                               href="#"
                               id="show-questions-management"
                               style="color: #34364A; white-space: nowrap; font-size: 16px;">
                                <i class="fas fa-question-circle" style="margin-right: 12px;"></i>
                                Questions Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center"
                               href="/select/teacher/submissions"
                               style="color: #34364A; white-space: nowrap; font-size: 16px;">
                                <i class="fas fa-user-graduate" style="margin-right: 12px;"></i>
                                Student Submissions
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="col content px-4" id="main-table-content" style="margin-left: 240px;">
                {{-- Tabel topik akan dimuat di sini via AJAX --}}
            </div>
        </div>
    </div>
    {{-- FOOTER --}}
    <footer class="footer">
        © 2025 Your Website. All rights reserved.
    </footer>   

    <script>
        // Script untuk menampilkan nama file .sql pada modal Add Topic
        $(document).on('change', '#add_schema_file', function() {
            $('#add_schema_filename').val(this.files.length ? this.files[0].name : 'No file chosen');
        });
    </script>
</body>
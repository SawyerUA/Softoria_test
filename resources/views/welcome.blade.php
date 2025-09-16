<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Softoria Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css" />
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        input, button { padding: 8px; margin: 6px 0; width: 100%; max-width: 400px; }
        .result { margin-top: 20px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center" style="margin-top: 50px;">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6" style="max-width: 500px;">
            <h1 class="mb-3 text-center">SEO Checker</h1>

            <form id="seoForm">
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="searched_word" class="form-label">Searched word</label>
                        <input type="text" class="form-control" id="searched_word" name="searched_word" placeholder="Пошукове слово">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="website_name" class="form-label">Website name</label>
                        <input type="text" class="form-control" id="website_name" name="website_name" placeholder="Назва сайту">
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" placeholder="Локація" value="Ukraine">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="language" class="form-label">Language</label>
                        <input type="text" class="form-control" id="language" name="language" placeholder="Мова" value="en">
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="result" id="result" style="display: none">

    </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#seoForm').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serialize();
        const resultBox = $('#result');

        resultBox.hide();
        apiRequest();

        function createTableHtml(){

            return `<table id="table" class="display">
                        <thead style="font-size: 14px;"></thead>

                        <tbody  style="font-size: 12px;"></tbody>
                    </table>`;
        }

        function apiDeniedHtml(){
            return `<p class="d-flex justify-content-center text-danger" >Вибачте, сайт не знайдено. Спробуйте інший</p>`;
        }

        function renderTable(data){

            if(data){

                resultBox.show();
                resultBox.html(createTableHtml());

                const tableId = $('#table');

                let tableData;

                const dataArray = Object.values(data);

                tableData = dataArray.map(item => [
                    item.website_name ?? '',
                    item.type ?? '',
                    item.rank_group ?? '',
                    item.rank_absolute ?? '',
                    item.domain ?? ''
                ]);

                tableId.DataTable({
                    data: tableData,
                    dom: 't',
                    "pageLength": 50,
                    columns: [
                        { title: "WebSite Name" },
                        { title: "Type" },
                        { title: "Rank Group" },
                        { title: "Rank Absolute" },
                        { title: "Domain" },
                    ],
                    columnDefs: [
                        { width: "150px", targets: 0 },
                        { className: "dt-center", targets: "_all" },
                    ]
                });

            }else{
                resultBox.hide();
            }
        }

        function renderDeniedMsg(){
            resultBox.show();
            resultBox.html(apiDeniedHtml());
        }

        function apiRequest(){

            Swal.fire({
                title: 'Зачекайте, сбір данних...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('search') }}",
                method: "POST",
                data: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(data) {
                    Swal.close();
                    renderTable(data);
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    renderDeniedMsg();
                }
            });
        }
    });
</script>
</html>

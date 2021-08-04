<!DOCTYPE html>
<html lang="en">
<head>
    <title>Niceye</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <style>
        .fakeimg {
            height: 200px;
            background: #aaa;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
        .fas:hover{
            cursor: pointer;
        }
        input:checked + .slider {
            background-color: red;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .alert {
            padding: 20px;
            background-color: lightblue;
            color: black;
            display: none;
        }

        .closebtn {
            margin-left: 15px;
            color: black;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }

        .dataTables_wrapper {
            width: 100%;
        }

    </style>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">
    <h1>Niceye Back-end Başvuru</h1>
    <p>Adnan Mehrat</p>
</div>

<div class="container" style="margin-top:30px">
    <div class="alert" style="width: 50%;margin-left: 25%;height: 150px">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <strong style="float:left">Info!</strong > <p id="alert_data" style="text-align: center" ></p>
    </div>
</div>
<div class="container" style="margin-top:30px">
    <div class="row">
        <br><br><br>
        <hr>

        <br><br><br>
        <hr>
        <table id="table_id" class="display">
            <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Birth Date</th>
                <th>Delete</th>
                <th>Update</th>
            </tr>
            </thead>
            <tbody id="customerTable">


            </tbody>

            <div style="width: 75%;float: left;">
                <form action="/addNewCustomerPage">
                    <button type="submit" class="btn btn-primary">Add New Customer</button>
                </form>
            </div>
            <div style="width: 25%;float: right"><strong id="deletionStatus"> Delete All</strong>
                <label style="margin-left: 5px" class="switch">
                    <input id="checkALL" onclick="deleteRedeleteAllCustomer(this)" type="checkbox" >
                    <span  class="slider"></span>
                </label>
            </div>
        </table>
        <br><br><br>
        <hr><hr>
    </div>

</div>




<script>
    $(document).ready( function () {
        listCustomers();
    });

    function listCustomers(){
        $("#customerTable").empty();
        $.ajax({
            url: '/customer/selectAll',
            async: false,
            success: function(data) {
                var customers = data.data.message.data;
                for(let x = 0 ; x < customers.length ; x++){
                    var is_delete =
                        '<label class="switch">'+
                        '<input  class="check"  onclick="deleteReDeleteCustomer(this,'+customers[x].costumer_id+')"  type="checkbox">'+
                        '<span  class="slider"></span>'+
                        '</label>';
                    if(customers[x].is_deleted == 1){
                        is_delete =
                            '<label class="switch">'+
                            '<input class="check" onclick="deleteReDeleteCustomer(this,'+customers[x].costumer_id+')" checked type="checkbox">'+
                            '<span  class="slider"></span>'+
                            '</label>';
                    }
                    $("#customerTable").append(
                        '<tr>'+
                        '<td>'+(x+1)+'</td>'+
                        '<td>'+customers[x].full_name+'</td>'+
                        '<td>'+customers[x].email+'</td>'+
                        '<td>'+customers[x].birth_date+'</td>'+
                        '<td>'+is_delete+'</td>'+
                        '<td><i onclick="updateCustomer('+customers[x].costumer_id+')" class="fas fa-edit" style="font-size:30px;color:dodgerblue"></i></td>'+
                        '</tr>'
                    );
                }
            }
        });
        $('#table_id').DataTable();

        if ($('.check:checked').length == $('.check').length) {
            $("#deletionStatus").text("re Delete ALL");
            $("#checkALL").prop('checked', true);
        }
    }

    function deleteReDeleteCustomer(check,id){
        if ($(check).is(':checked')) {
            $.ajax({
                url: '/customer/delete/'+id,
                async: false,
                success: function(data) {
                    var data = data.data.message.data;
                    $("#alert_data").text(data)
                    $(".alert").show();
                    setTimeout(function(){ $(".alert").hide() },3000);
                }
            });

        }else{
            $.ajax({
                url: '/customer/re/delete/'+id,
                async: false,
                success: function(data) {
                    var data = data.data.message.data;
                    $("#alert_data").text(data)
                    $(".alert").show();
                    setTimeout(function(){ $(".alert").hide() },3000);
                }
            });
        }
        if ($('.check:checked').length != $('.check').length) {
            $("#deletionStatus").text("Delete ALL");
            $("#checkALL").prop('checked', false);
        }else{
            $("#deletionStatus").text("re Delete ALL");
            $("#checkALL").prop('checked', true);
        }
    }

    function deleteRedeleteAllCustomer(check){
        if ($(check).is(':checked')) {
            $.ajax({
                url: '/customer/deleteAll',
                async: false,
                success: function(data) {
                    var data = data.data.message.data;
                    $("#alert_data").text(data)
                    $(".alert").show();
                    setTimeout(function(){ $(".alert").hide() },3000);
                }
            });
            $("#deletionStatus").text("re Delete ALL");
        }else{
            $.ajax({
                url: '/customer/re/deleteAll',
                async: false,
                success: function(data) {
                    var data = data.data.message.data;
                    $("#alert_data").text(data)
                    $(".alert").show();
                    setTimeout(function(){ $(".alert").hide() },3000);
                }
            });
            $("#deletionStatus").text("Delete ALL");
        }
        listCustomers();
    }

    function updateCustomer(id){
        window.location.href = "updateCustomerPage/"+id;
    }

</script>
</body>
</html>

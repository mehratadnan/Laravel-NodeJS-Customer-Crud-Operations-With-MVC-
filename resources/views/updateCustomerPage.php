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
    <form style="float: left;width: 25%" action="/">
        <button  type="submit" class="btn btn-primary">list All Customers</button>
    </form>
    <br><br><br>
    <div class="row">
        <br><br><br>
        <hr>
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong style="float:left">Info!</strong> <p id="alert_data" style="float:right;margin-left: 10px" ></p>
        </div>


        <br><br><br>
        <hr>
        <form  type="post" id="updateCustomer" style="width: 100%"  enctype="multipart/form-data">
            <h2 style="text-align: left">update Customer</h2>
            <div class="form-group">
                <label for="full_name">Name:</label>
                <input type="text" class="form-control" id="full_name" name="full_name">
            </div>
            <div class="form-group">
                <label for="email">email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="birth_date">birth date:</label>
                <input type="date" class="form-control" id="birth_date" name="birth_date">
            </div>
            <input type="text" class="form-control" id="costumer_id" hidden name="costumer_id">
            <button type="submit"  class="btn btn-primary">Submit</button>
        </form>
    </div>

</div>




<script>


    $(document).ready( function () {
        var url = window.location.href;
        url = url.split("/");
        var id = url[url.length - 1];
        $.ajax({
            type: "GET",
            url: '/customer/select/'+id,
            dataType: "json",
            encode: true,
            success: function(data){
                var customer = data.data.message.data[0];
                $("#full_name").val(customer.full_name);
                $("#email").val(customer.email);
                $("#password").val(customer.password);
                $("#birth_date").val(customer.birth_date);
                $("#costumer_id").val(customer.costumer_id);
            },
            error: function (request, status, error) {
                var data = JSON.parse(request.responseText).data.error;
                $("#alert_data").empty().append(data)
                $(".alert").show();
                setTimeout(function(){ $(".alert").hide() },5000);
            }
        });
    });

    $("#updateCustomer").submit(function (event) {
        var formData = {
            full_name: $("#full_name").val(),
            email: $("#email").val(),
            password: $("#password").val(),
            birth_date: $("#birth_date").val(),
        };

        $.ajax({
            type: "POST",
            url: '/customer/update/'+$("#costumer_id").val(),
            data: formData,
            dataType: "json",
            encode: true,
            success: function(data){
                var data = data.data.message.data;
                $("#alert_data").empty().text(data)
                $(".alert").show();
                $("#addCustomer").trigger("reset");
                setTimeout(function(){ $(".alert").hide() },3000);

            },
            error: function (request, status, error) {
                var data = JSON.parse(request.responseText).data.error;
                var str = "";
                for (var key in data) {
                    str=str + "<string>"+key+" : "+data[key]+"</strong><br>"
                }
                $("#alert_data").empty().append(str)
                $(".alert").show();
                setTimeout(function(){ $(".alert").hide() },5000);
            }
        });
        event.preventDefault();
    });


</script>
</body>
</html>

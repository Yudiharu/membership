@extends('adminlte::page')

@section('title', 'Membership System')

@section('content_header')
    <h1>Dashboard</h1>
@stop
<style>
.body, .content {
    background: url("nature1.jpg");
    background-size:cover;
    overflow: hidden;
}
.panel-title {
  font-family: cursive;
}
.panel-heading {
  background: url("blackvector.jpg");
}
</style>
@section('content')
<body onLoad="load()">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title" style="font-size: 25px"><marquee><b><i><font style="color: silver; text-shadow: 0 0 10px #FBBC5C, 0 0 70px #9856f5, 0 0 30px #9856f5, 0 0 40px #9856f5, 0 0 50px #FBBC5C, 0 0 60px #FBBC5C, 0 0 70px #FBBC5C;">Daftar Tenaga Kerja Non Pegawai</font></i></b></marquee></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <div class="box box-default collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><font style="font-size: 15px">Users Login </font><span class="badge bg-blue">{{ $leng4 }}</span></h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
              <div class="box-body">
                @foreach($user_login as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td><span class="badge bg-purple">GUI</span><br></td>
                </tr>
                @endforeach
              </div>
          </div>
        </div>
      </div>  
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<style type="text/css">
  #back2Top {
    width: 400px;
    line-height: 27px;
    overflow: hidden;
    z-index: 999;
    display: none;
    cursor: pointer;
    position: fixed;
    bottom: 0;
    text-align: left;
    font-size: 15px;
    color: #000000;
    text-decoration: none;
  }
  #back2Top:hover {
    color: #fff;
  }

  /* Button used to open the contact form - fixed at the bottom of the page */
  .open-button {
  background-color: #0275d8;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 70px;
  right: 28px;
  width: 54px;
  border-radius: 50%;
  }

  /* The popup chat - hidden by default */
  .chat-popup {
    display: none;
    position: fixed;
    bottom: 25px;
    right: 15px;
    z-index: 9;
  }

  /* Add styles to the form container */
  .form-container {
    max-width: 200px;
    max-height: 350px;
    padding: 10px;
    background-color: white;
  }

  /* Full-width textarea */
  .form-container textarea {
    width: 100%;
    padding: 15px;
    margin: 5px 0 22px 0;
    border: none;
    background: #f1f1f1;
    resize: none;
    min-height: 100px;
  }

  /* When the textarea gets focus, do something */
  .form-container textarea:focus {
    background-color: #ddd;
    outline: none;
  }

  /* Set a style for the submit/send button */
  .form-container .btn {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    margin-bottom:10px;
    opacity: 0.8;
  }

  /* Add a red background color to the cancel button */
  .form-container .cancel {
    background-color: red;
  }

  /* Add some hover effects to buttons */
  .form-container .btn:hover, .open-button:hover {
    opacity: 1;
  }

  .close {
    position: fixed;
    bottom: 362px;
    right: 23px;
    color: red;
    font-size: 15px;
  }


  /* Button used to open the contact form - fixed at the bottom of the page */
    .open-button2 {
    background-color: #CD853F;
    color: black;
    padding: 4px 8px;
    border: none;
    cursor: pointer;
    opacity: 0.8;
    position: fixed;
    bottom: 36px;
    right: 28px;
    width: 110px;
  }

  /* The popup form - hidden by default */
  .form-popup2 {
    display: none;
    position: fixed;
    bottom: 26px;
    right: 15px;
    z-index: 9;
  }

  .close2 {
    position: fixed;
    bottom: 44px;
    right: 28px;
    color: red;
    font-size: 15px;
  }

  /* Add styles to the form container */
  .form-container2 {
    max-width: 300px;
    padding: 10px;
    background-color: white;
  }

  /* Full-width input fields */
  .form-container2 input[type=text], .form-container input[type=password] {
    width: 100%;
    padding: 15px;
    margin: 5px 0 22px 0;
    border: none;
    background: #f1f1f1;
  }

  /* When the inputs get focus, do something */
  .form-container2 input[type=text]:focus, .form-container input[type=password]:focus {
    background-color: #ddd;
    outline: none;
  }

  /* Set a style for the submit/login button */
  .form-container2 .btn {
    background-color: #4CAF50;
    color: white;
    padding: 16px 20px;
    border: none;
    cursor: pointer;
    width: 100%;
    margin-bottom:10px;
    opacity: 0.8;
  }

  /* Add a red background color to the cancel button */
  .form-container2 .cancel {
    background-color: red;
  }

  /* Add some hover effects to buttons */
  .form-container2 .btn:hover, .open-button:hover {
    opacity: 1;
  }

  .news-button {
    background-color: #F63F3F;
    bottom: 40px;
  }

  .chat-button {
    background-color: #FDA900;
    bottom: 70px;
  }

  #mySidenav button {
    position: fixed;
    right: -30px;
    transition: 0.3s;
    padding: 4px 8px;
    width: 70px;
    text-decoration: none;
    font-size: 12px;
    color: white;
    border-radius: 5px 0 0 5px ;
    opacity: 0.8;
    cursor: pointer;
    text-align: left;
  }

  #mySidenav button:hover {
    right: 0;
  }
</style>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}

function openForm2() {
  document.getElementById("myForm2").style.display = "block";
}

function closeForm2() {
  document.getElementById("myForm2").style.display = "none";
}
</script>

</body>
@stop

@push('js')
      <script type="text/javascript">
        
        

        function load(){
            startTime();
            $('.tombol1').hide();
            $('.tombol2').hide();
           
        }
      </script>
@endpush
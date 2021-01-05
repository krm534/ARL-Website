<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!isset($_SESSION)) 
{ 
 session_start(); 
} 

// Connect to database
require_once("DatabaseConnection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>ARL Website</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }

    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

    .md-form {
      margin-bottom: 30px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }

    .pagingSection {
      text-align: center;
    }

    .pagingSection p {
      display: inline-block;
    }

    .pageLeft {
      margin-right: 25px;
    }

    .pageRight {
      margin-left: 25px;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Laurel Animal Rescue League</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="index.php">Home</a></li>
          <li class="active"><a href="DogPage.php">Available Dogs</a></li>
          <li><a href="CatPage.php">Available Cats</a></li>
        </ul>
        <?php
 if (empty($_SESSION["email"]) == true){
 ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
        </ul>
        <?php
 }
 else { ?>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Logout</a></li>
        </ul>
        <?php }
 ?>
      </div>
    </div>
  </nav>

  <!-- Body -->

  <body>
    <?php require_once 'ARLwebsite.php'; ?>
    <?php
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?=$_SESSION['msg_type'] ?>">
      <?php
 echo $_SESSION['message'];
 unset($_SESSION['message']);
?>
    </div>
    <?php endif; ?>

    <div class="container">
      <div class="jumbotron">
        <div class="container">
          <h1 class="text-center">Available Dogs</h1>
          <div style="display: block; text-align:center; margin-bottom: 30px;">
            <div class="searchBar" style="display: inline-block;">
              <form>
                <input type="text" class="searchBarInput" name="searchBarInput" placeholder="Search by...">
                <select name="searchType" id="searchType">
                  <option value="Name">Name</option>
                  <option value="Age">Age</option>
                  <option value="Breed">Breed</option>
                  <option value="Gender">Gender</option>
                </select>
              </form>
            </div>
          </div>
          <?php
 if (isset($_SESSION['results']) && isset($_SESSION['resultData'])) {
   $data = $_SESSION['resultData'];

   if ($_SESSION['results'] == 1) {
    $query = "SELECT * FROM animals WHERE type='dog' AND name='$data'";
    $result = $mysqli->query($query) or die($mysqli->error);
    echo "RESULT: " . $query;
    unset($_SESSION['results']);
   }
   else if ($_SESSION['results'] == 2) {
    $query = "SELECT * FROM animals WHERE type='dog' AND breed='$data'";
    $result = $mysqli->query($query) or die($mysqli->error);
    echo "RESULT: " . $query;
    unset($_SESSION['results']);
  }
  else if ($_SESSION['results'] == 3) {
    $query = "SELECT * FROM animals WHERE type='dog' AND gender='$data'";
    $result = $mysqli->query($query) or die($mysqli->error);
    echo "RESULT: " . $query;
    unset($_SESSION['results']);
  }
  else {
    $query = "SELECT * FROM animals WHERE type='dog' AND age='$data'";
    $result = $mysqli->query($query) or die($mysqli->error);
    echo "RESULT: " . $query;
    unset($_SESSION['results']);
  }
 }
 else {
  $result = $mysqli->query("SELECT * FROM animals WHERE type='dog'") or die($mysqli->error);
 }
?>
          <div class="row justify-content-center table-responsive">
            <table class='table'>
              <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>Breed</th>
                  <th>Age</th>
                  <th>Gender</th>
                </tr>
              </thead>
              <tbody class="dataContainer">
                <?php while ($row = $result->fetch_assoc()):?>
                  <tr class="dataRow">
                    <td style="display:none;"><?php echo $row['id'] ?></td>
                    <td><img src="<?php echo $row['image1'] ?>" width="200px" height="200px"></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['breed'] ?></td>
                    <td><?php echo $row['age'] ?></td>
                    <td><?php echo $row['gender'] ?></td>
                    <td style="display:none;"><?php echo $row['description'] ?></td>
                    <td><a href="Profile.php?id=<?php echo $row['id']?>" class="btn btn-default">View Profile</a></td>
                    <?php if (empty($_SESSION["email"]) == false) { ?>
                    <td><button type="button" class="btn btn-info editbtn">Edit</button></td>
                    <td><a href="ARLwebsite.php?delete=<?php echo $row['id']; ?>&type=dog" class="btn btn-danger">Delete</a></td>
                    <?php } ?>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
            <div class="pagingSection">
              <p class="pageLeft"><</p>
              <p class="pagingNumber"></p>
              <p class="pageRight">></p>
            </div>
          </div>

          <?php
 if (empty($_SESSION["email"]) == false){
?>
          <div class="text-center">
            <a href="" class="btn btn-success btn-rounded mb-4 " data-toggle="modal"
              data-target="#modalSubscriptionForm">Add Dog</a>
          </div>
          <?php } ?>
        </div>
      </div>


      <div class="container-fluid bg-3 text-center">
        <div class="row">

          <!-- MODAL -->
          <div class="modal fade" id="modalSubscriptionForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header text-center">

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <!-- MODAL body -->
                <div class="modal-body mx-3">

                  <form action="ARLwebsite.php" method="POST" enctype="multipart/form-data">

                    <div class="md-form mb-5">
                      <i class="fas fa-user prefix grey-text"></i>
                      <label data-error="wrong" data-success="right" for="form1">Name</label>
                      <input type="text" id="form1" class="form-control validate" name="name" required>
                    </div>

                    <div class="md-form mb-4">
                      <i class="fas fa-envelope prefix grey-text"></i>
                      <select name="breed" id="form2" required></select>
                    </div>
                    <div class="md-form mb-4">
                      <i class="fas fa-envelope prefix grey-text"></i>
                      <label data-error="wrong" data-success="right" for="form3">Age</label>
                      <input type="number" id="form3" class="form-control validate" name="age" required>
                    </div>
                    <div class="md-form mb-5">
                      <i class="fas fa-user prefix grey-text"></i>
                      <label data-error="wrong" data-success="right" for="form4">Description</label>
                      <textarea name="description" style="width:100%; height: 100px;" id="form4" required></textarea>
                    </div>
                    <div class="md-form mb-5">
                      <i class="fas fa-user prefix grey-text"></i>
                      <label data-error="wrong" data-success="right" for="form5">Gender</label>
                      <select id="form5" name="gender" id="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select>
                    </div>
                    <input type="hidden" value="dog" name="type">
                    <div class="md-form mb-4">
                      <label data-error="wrong" data-success="right" for="form6">Image 1</label>
                      <input type="file" name="image1" id="form6" required>
                    </div>
                    <div class="md-form mb-4">
                      <label data-error="wrong" data-success="right" for="form7">Image 2</label>
                      <input type="file" name="image2" id="form7" required>
                    </div>
                    <div class="md-form mb-4">
                      <label data-error="wrong" data-success="right" for="form8">Image 3</label>
                      <input type="file" name="image3" id="form8" required>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                      <button class="btn btn-indigo" name="add">Add<i class="fas fa-paper-plane-o ml-1"></i></button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit modal -->
      <div class="container-fluid bg-3 text-center">
        <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body mx-3" id="editmodal">
                <form action="ARLwebsite.php" method="POST" enctype="multipart/form-data">

                  <div class="md-form mb-5">
                    <i class="fas fa-user prefix grey-text"></i>
                    <label data-error="wrong" data-success="right" for="form3">Name</label>
                    <input type="text" id="name" class="form-control validate" name="name">
                  </div>

                  <div class="md-form mb-4">
                    <i class="fas fa-envelope prefix grey-text"></i>
                    <label data-error="wrong" data-success="right" for="form2">Breed</label>
                    <input type="text" id="breed" class="form-control validate" name="breed">
                  </div>
                  <div class="md-form mb-4">
                    <i class="fas fa-envelope prefix grey-text"></i>
                    <label data-error="wrong" data-success="right" for="form2">Age</label>
                    <input type="text" id="age" class="form-control validate" name="age">
                  </div>
                  <div class="md-form mb-5">
                    <i class="fas fa-user prefix grey-text"></i>
                    <label data-error="wrong" data-success="right" for="description">Description</label>
                    <textarea name="description" style="width:100%; height: 100px;" id="description"
                      required></textarea>
                  </div>
                  <div class="md-form mb-5">
                    <i class="fas fa-user prefix grey-text"></i>
                    <label data-error="wrong" data-success="right" for="form3">Gender</label>
                    <select id="gender" name="gender">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                  <input type="hidden" value="dog" name="type">
                  <input type="hidden" id="ID" name="id">
                  <div class="modal-footer d-flex justify-content-center">
                    <button class="btn btn-indigo" id="updatedata" name="updatedata">Update <i
                        class="fas fa-paper-plane-o ml-1"></i></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </body>
</html>

<!-- Javascript -->
<script>
  $(document).ready(function () {
    var pagingNumber = 1;

    // Set paging number on page load
    $(".pagingNumber").text(pagingNumber);

    // DOG API CALL
    async function apiCall() {
      let option = "";
      let response = await fetch("https://dog.ceo/api/breeds/list/all");
      let data = await response.json();
      for (let [key, value] of Object.entries(data.message)) {
        if (value !== null && value.length == 0) {
          option += "<option value=" + key + ">" + key + "</option>";
        }
        else {
          for (let animal of value) {
            option += "<option value=" + animal + " " + key + ">" + animal + " " + key + "</option>";
          }
        } 
      }

      $("#form2").append(option);
      return data;
    }
    apiCall().then(data => console.log(data));

    // AJAX FOR RECEIVING DATA ASYNCHRONOUSLY
    $('.searchBarInput').on('input', (e) => {
      var searchType = $('#searchType').val();
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var response = JSON.parse(this.responseText);
          populateSearchDataIntoUI(response);
        }
      };
      xmlhttp.open("GET", "AsyncDogSearch.php?q=" + e.target.value + "&t=" + searchType, true);
      xmlhttp.send();
    });

    // Populate returned searched data into UI
    function populateSearchDataIntoUI(response) {
      var allDataHolder = "";
      var dataContainer = $('.dataContainer');
      dataContainer.empty();

      populateTableWithAdminSpecificContent((secondResponse) => {
        for (var i = 0; i < Object.keys(response).length; i++) {
        allDataHolder += '<tr class="dataContainer">';
        allDataHolder += '<td style="display:none">' + response[i].id + "</td>";
        allDataHolder += '<td><img src="' + response[i].image1 + '" width="200px" height="200px"></td>';
        allDataHolder += '<td>' + response[i].name + '</td>';
        allDataHolder += '<td>' + response[i].breed + '</td>';
        allDataHolder += '<td>' + response[i].age + '</td>';
        allDataHolder += '<td>' + response[i].gender + '</td>';
        allDataHolder += '<td><a href="Profile.php?id=' + response[i].id + '" class="btn btn-default">View Profile</a></td>';
        if (secondResponse == "true"){
          allDataHolder += '<td><button type="button" class="btn btn-info editbtn">Edit</a></td>';
          allDataHolder += '<td><a href="ARLwebsite.php?delete=' + response[i].id + '&type=dog" class="btn btn-danger">Delete</a></td>';
        }
        allDataHolder += "</tr>";
      }

      dataContainer.append(allDataHolder);
      });
    }

    // Check if session is set
    function populateTableWithAdminSpecificContent(callback) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          callback(this.responseText);
        }
      };
      xmlhttp.open("GET", "CheckSession.php", true);
      xmlhttp.send();
    }

    // EDIT BUTTON FUNCTIONALITY
    $(document).on('click', '.editbtn', function () {
      $tr = $(this).closest('tr');

      var data = $tr.children("td").map(function () {
        return $(this).text();
      }).get();

      $('#ID').val(data[0].trim())
      $('#name').val(data[2].trim());
      $('#age').val(data[4].trim());
      $('#breed').val(data[3].trim());
      $('#gender').val(data[5].trim());
      $('#description').val(data[6].trim());

      $("#editmodal").modal('show');
    });
  });
</script>
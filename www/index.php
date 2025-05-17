<?php
  session_start();
  require_once("./admin/db-con.php");
  if (!isset($_SESSION['username'])) {
    header("Location: /pages/login.php");
    die();
  }

  $conn=create_connection();

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="/style.css"/>
  <title>Home Page</title>
</head>
<body style="background: green">

  <!-- Navigation -->
  <div class="container-fluid bg-light py-2">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <a class="btn btn-outline-primary m-2" href="/pages/logout.php">Signout</a>

      <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') { ?>
        <a href="/admin/db.php" class="btn btn-primary m-2">Go to Admin Page</a>
      <?php } ?>
    </div>
  </div>
  <?php 
    $username=$_SESSION['username'];
    $sql='SELECT is_activated FROM user WHERE display_name=?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();


    if($row['is_activated']===0){?>
      <div class="alert alert-warning position-fixed top-0 end-0 m-3 p-3 w-25" style="z-index: 1055;">
  Please activate your account using the email sent to you.
</div>

    <?php }
  ?>
  <!-- Header -->
  <div class="container text-center my-4 bg-dark text-white">
    <h1 >WELCOME TO NOTETATION, <?= $_SESSION['username'] ?></h1>
  </div>

  <!-- Create + Search -->
  <div class="container mb-4">
    <div class="row align-items-center">
      <div class="col-12 col-md-3 mb-2">
        <button type="button" class="btn btn-warning w-100" data-toggle="modal" data-target="#myModal">Create Note</button>
      </div>

      
      <div class="col-12 col-md-9">
        <form action="" method="get">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search notes..."/>
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">Find</button>
            </div>
          </div>
        </form>

      </div>
      <div class="col-12 col-md-2 mb-2">
        <div class="btn-group w-100">
          <button class="btn btn-outline-primary active" id="grid-view">
            <i class="fas fa-th-large"></i>
          </button>
          <button class="btn btn-outline-primary" id="list-view">
            <i class="fas fa-list"></i>
          </button>
        </div>
      </div>
      <div class="col-12 col-md-2 mb-2">
        <div class="btn-group w-100">
          <button class="btn btn-outline-primary active" id="grid-view">
            <i class="fas fa-th-large"></i>
          </button>
          <button class="btn btn-outline-primary" id="list-view">
            <i class="fas fa-list"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Note Form by TaPu-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <form id="noteForm" action="Note/save_note.php" method="POST" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <input id="title" class="form-control mb-2" type="text" name="title" placeholder="Title" />
        </div>
        <div class="modal-body">
          <input type="hidden" name="note_id" value="<?= $_GET['id'] ?? '' ?>">
          <textarea name="content" placeholder="Content" id="content" style="width: 100%; height:150px"></textarea>
        </div>
        <!-- 
        <div class="modal-body">
          <input type="file" name="image" id="imageInput" class="custom-file-input" accept="image/*">
          <label for="imageInput" class="custom-file-label" style="padding:8px 12px;">Choose Image</label>
          <br><br>
          <span id="file-name" style="color:red;"></span>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" id="saveBtn" data-dismiss="modal">Close</button>
        </div>
        -->
      </div>
    </form>

  </div>
</div>

  <!-- Note Display -->
  <div class="container">
  <div class="row d-flex">
    <button onclick="toggleView('grid')" href="Node/display_note.php">Grid View</button>
    <button onclick="toggleView('list')">List View</button>
  </div>
  
  <div id="notesContainer" class="row row-cols-3">
    <?php
    require_once('admin/db-con.php');
    $conn = create_connection();

  
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $stmt = $conn->prepare("SELECT tieu_de, noi_dung FROM note WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($note = $result->fetch_assoc()):
    ?>
      <div class="col note-card">
        <h3><?= htmlspecialchars($note['tieu_de'] ?? '') ?></h3>
        <p><?= htmlspecialchars($note['noi_dung'] ?? '') ?></p>
        <?php if (!empty($note['image_path'])): ?>
          <img src="<?= htmlspecialchars($note['image_path']) ?>" width="100">
        <?php endif; ?>
      </div>
    <?php
        endwhile;
        $stmt->close();
    } else {
        echo '<p>Vui lòng đăng nhập để xem ghi chú.</p>';
    }

    $conn->close();
    ?>
  </div>
</div>

      <?php 
	  	$sql="select * from user,note";
	 
	 	for ($i = 0; $i < 50; $i++) { ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 note-item">
          <div class="border rounded p-3 shadow-sm h-100 bg-white">
            <!-- Title & 3-dot menu -->
            <div class="d-flex justify-content-between align-items-start">
              <div class="font-weight-bold text-truncate" style="max-width: 85%;">Title <?= $i ?></div>

              <div class="dropdown">
                <button class="btn btn-sm btn-light p-1" type="button" data-toggle="dropdown" aria-expanded="false">
                  <span class="text-dark"><i class="fa-solid fa-ellipsis-vertical"></i></span>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a class="dropdown-item" href="#">Modify</a>
                  <a class="dropdown-item text-danger" href="#">Delete</a>
                </div>
              </div>
            </div>

            <!-- Note content -->
            <div class="text-muted text-truncate mt-2">hehehhe, dhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh <?= $i ?></div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="/main.js"></script>
</body>
</html>

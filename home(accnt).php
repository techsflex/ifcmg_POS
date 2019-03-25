<?php
  /* Displays user information and some useful messages */
  session_start();
  include "config.php";
  // Check if user is logged in using the session variable
  if (!$_SESSION['logged_in']) {
      $_SESSION['message'] = "You must log in before viewing your profile page!";
      header("location: error.php");    
  }

  else {
    // Makes it easier to read
	$userID = $_SESSION['userID'];
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $status = $_SESSION['status'];
	$default_companyID = $_SESSION['defaultcompanyID'];
	$default_company = $_SESSION['defaultcompany'];
	$role = $_SESSION['role'];

    $company = array();
	if ($status==='5') {
		$query = "SELECT company_companyID, role_roleID FROM membership";	
	}
    else {
		$query = "SELECT company_companyID, role_roleID FROM membership WHERE users_userID='$userID'";	
	}
    if ($result = $conn->query($query)) {
        while ($row = $result->fetch_assoc()){
            $company[(int)$row['company_companyID']] = $row['role_roleID']; 
        }
    }
  }
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IFCMG - Accounting Software</title>
    <meta name="description" content="IFCMG - Accounting Software">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="images/logoText.png">
	
	
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="assets/scss/style.css">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet" type="text/css">
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	

</head>
<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="./"><img src="images/logoText.png" alt="Logo"></a>
                <a class="navbar-brand hidden" href="./"><img src="images/logoText.png" alt="Logo"></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
					<?php
						if ($default_companyID != "0" && ($role == "2" || $role == "3" || $role =="4"  || $status === "5")) {
							echo "<li class='active' onClick='loadPage(this)' id='dashboard' value='0'>";
								echo "<a><i class='menu-icon fa fa-dashboard'></i>Dashboard</a>";
							echo "</li>";
						}
					?>
                    
                    
					<h3 class="menu-title">Accounting</h3><!-- /.menu-title -->
					<?php
						if ($default_companyID != "0" && ($role === "2" || $role === "3" || $role ==="4" || $status === "5")) {
							
							echo "<li onClick='loadPage(this)' id='newPost' value='1'>";
								echo "<a><i class='menu-icon ti-plus'></i>New Post</a>";
							echo "</li>";

							echo "<li onClick='loadPage(this)' id='editPost' value='2'>";
								echo "<a><i class='menu-icon ti-pencil-alt'></i>Edit Posts</a>";
							echo "</li>";

							echo "<li onClick='loadPage(this)' id='queryTransaction' value='3'>";
								echo "<a><i class='menu-icon ti-info-alt'></i>Query Posts</a>";
							echo "</li>";

							echo "<li onClick='loadPage(this)' id='trashPost' value='4'>";
								echo "<a><i class='menu-icon ti-trash'></i>Trash</a>";
							echo "</li>";
						}
					?>
					
					<h3 class='menu-title'>Settings</h3>
					<?php
						if ($default_companyID != "0" && $status === "5") {
							echo "<li onClick='loadPage(this)' id='accountSetup' value='5'>";
								echo "<a><i class='menu-icon ti-list'></i>Account Setup</a>";
							echo "</li>";
						}

						if ($default_companyID != "0" && ($status === "5" || $role === "2")) {
							echo "<li onClick='loadPage(this)' id='companySetup' value='6'>";
								echo "<a><i class='menu-icon ti-settings'></i>Company Setup</a>";
							echo "</li>";
						}
						
						if ($default_companyID != "0" && ($status === "5" || $role === "2" || $role === "3")) {
							echo "<li onClick='loadPage(this)' id='masterCaptionSetup' value='7'>";
                    			echo "<a> <i class='menu-icon ti-clip'></i>Master Caption Setup</a>";
							echo "</li>";
						}
					
						if ($default_companyID != "0" && ($status === "5" || $role === "2" || $role === "3" || $role === "4")) {
							echo "<li onClick='loadPage(this)' id='accountCaptionSetup' value='8'>";
                    			echo "<a> <i class='menu-icon ti-tag'></i>Account Caption Setup</a>";
							echo "</li>";
						}
					?>
					

                    <h3 class="menu-title">Other Options</h3><!-- /.menu-title -->
                    <li>
                        <a href="logout.php"> <i class="menu-icon ti-control-eject"></i>Logout</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->
    <!-- END Left Panel -->

    <!-- START Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->
        <header id="header" class="header">
            <div class="header-menu">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
        					<div class="page-header float-left">
        						<div class="page-title">
        							<h1 id="breadcrumb"></h1>
        						</div>
        					</div>
        					
        					<div class="page-header float-right">
        						<div class="page-title">
        							<h1>Welcome, <?php echo $first_name . " " . $last_name?></h1>
        						</div>
        					</div>
                        </div>
                    </div>
                    
                    <div class="row">
                    <!--Search bar-->
                        <label style="padding-top: 5px;"><strong>Company Name:&nbsp;</strong></label>
                        <select class="companySelection" id="companySelection" tabindex="1">
                            <?php
								$default_company = $_SESSION['defaultcompany'];
								$default_companyID = $_SESSION['defaultcompanyID'];
								$role = $_SESSION['role'];
								$caps_default_company = strtoupper($default_company);
								echo "<option value='$default_companyID'>$caps_default_company</option>";
								    
                                foreach(array_keys($company) as $com) {
                                    $query = "SELECT * FROM company WHERE companyID='$com'";
                                    $result = $conn->query($query);
                                    $result_company = $result->fetch_assoc();
                                    $company_name = $result_company['companyname'];
                                    $caps_company_name = strtoupper($result_company['companyname']);
									if ($default_companyID != $com) {
										echo "<option value='$com'>$caps_company_name</option>";	
									}
                                }
								$conn->close();
                            ?>
                        </select>
                    </div>
                </div>
                
        	</div>

        </header><!-- /header -->
        <!--END Header-->
        <div class="content mt-3" id="main_content">
			
        </div> <!-- .content -->
    </div><!-- /#right-panel -->
    <!-- Right Panel -->
	
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="js/updateSession.js"></script>
	<script>
		function loadPage(el){
			//alert(el.value);

		   $(el).siblings('li').removeClass('active');
		   $(el).addClass('active');

			if(el.value==0){
				//Redirect to Dashboard

				$("#main_content").load("dashboard.php");
				//$("#breadcrumb").text("Dashboard");
			}
			
			else if(el.value==1){
				//Add New Post

				 $("#main_content").load("newpost.php");
				 //$("#breadcrumb").text("New Post");
			}
			
			else if(el.value==2){
				//Modify On-hold Posts

				 $("#main_content").load("editpost.php");
				 //$("#breadcrumb").text("Edit Posts");
			}
			
			else if(el.value==3){
				//Query confirmed Posts
				 $("#main_content").load("queryTransactions.php");
				 //$("#breadcrumb").text("Query Transactions");
			}
			
			else if(el.value==4){
				//View Deleted Posts
				$("#main_content").load();
				//$("#breadcrumb").text("Deleted Posts");
			}

			else if(el.value==5){
				//Setup New Account (for admin only)
				$("#main_content").load();
				//$("#breadcrumb").text("Account Setup");
			}
			
			else if(el.value==6){
				//Setup Company (for admin + Superuser)
				$("#main_content").load();
				//$("#breadcrumb").text("Company Setup");
			}
			
			else if(el.value==7){
				//Create new master caption (for admin + superuser)
				$("#main_content").load("masterCaptionSetup.php");
				//$("#breadcrumb").text("Master Caption Setup");
			}
			
			else if(el.value==8){
				//Create new account caption (for all roles)
				$("#main_content").load("accountCaptionSetup.php");
				//$("#breadcrumb").text("Account Caption Setup");
			}

		}
	</script>
	
</body>
</html>

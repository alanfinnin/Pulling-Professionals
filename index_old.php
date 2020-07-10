<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
<?php 
require_once("resources/templates/navigation.php");
require_once("resources/library/databaseFunctions.php");
?>

    <div class="container border-danger">
        <h1 style="margin-top: 25px;">Home</h1>
        <ol class="breadcrumb" style="margin-top: 15px;">
            <li class="breadcrumb-item"><a href="#"><span>Home</span></a></li>
            <li class="breadcrumb-item"><a href="#"><span>Library</span></a></li>
            <li class="breadcrumb-item"><a href="#"><span>Data</span></a></li>
        </ol>
    </div>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-md-4" style="max-width: 15%;">
                    <div class="dropdown"><button class="btn btn-dark dropdown-toggle text-left" data-toggle="dropdown" aria-expanded="true" type="button" style="margin-right: 10px;margin-left: 10px;margin-top: 10px;">Actions</button>
                        <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">View Users</a><a class="dropdown-item" role="presentation" href="#">Banned Users</a><a class="dropdown-item" role="presentation" href="#">Recent Activity</a></div>
                    </div>
                </div>
                <div class="col-auto col-md-8" style="max-width: auto;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Banned</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cell 1</td>
                                    <td>Cell 2</td>
                                    <td>Cell 3</td>
                                    <td>Cell 3</td>
                                </tr>
                                <tr>
                                    <td>Cell 3</td>
                                    <td>Cell 4</td>
                                    <td>Cell 3</td>
                                    <td>Cell 3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("resources/templates/footer.php");?>
</body>
</html>
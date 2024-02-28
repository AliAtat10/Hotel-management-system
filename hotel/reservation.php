<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
<div class="container">
<img class="img-responsive" src="images/ac.png" style="width:100%; height:180px;"/>
</div>
</body>
</html>
<?php
    include_once 'admin/include/class.user.php'; 
    $user=new User();

    if(isset($_REQUEST[ 'submit'])) 
    { 
        extract($_REQUEST); 
        $result=$user->check_available($checkin, $checkout);
        if(!($result))
        {
            echo $result;
        }


    }
        
    if(isset($_POST['search'])) {
        $checkin = $_POST['checkin'];
        $checkout = $_POST['checkout'];
        $roomCategory = $_POST['room_category'];
        $maxPrice = isset($_POST['price']) ? $_POST['price'] : PHP_INT_MAX;

        $checkin = mysqli_real_escape_string($user->db, $checkin);
        $checkout = mysqli_real_escape_string($user->db, $checkout);
        $roomCategory = mysqli_real_escape_string($user->db, $roomCategory);

        $query = "SELECT * FROM room_category WHERE available > 0 AND price <= $maxPrice";

        if (!empty($checkin)) {
            $query .= " AND roomname NOT IN (SELECT room_cat FROM rooms WHERE checkout >= '$checkin' AND checkin <= '$checkout')";
        }

        if (!empty($roomCategory) && $roomCategory !== 'all') {
            $query .= " AND roomname = '$roomCategory'";
        }

        $result = mysqli_query($user->db, $query);

        if ($result) {
            while ($row = mysqli_fetch_array($result)) {
                $room_cat = $row['roomname'];
                $sql = "SELECT * FROM room_category WHERE roomname='$room_cat'";
                $query = mysqli_query($user->db, $sql);
                $row2 = mysqli_fetch_array($query);

                echo "
                    <div class='row'>
                        <div class='col-md-4'></div>
                        <div class='col-md-5 well'>
                            <h4>".$row2['roomname']."</h4><hr>
                            <h6>No of Beds: ".$row2['no_bed']." ".$row2['bedtype']." bed.</h6>
                            <h6>Available Rooms: ".$row2['available']."</h6>
                            <h6>Facilities: ".$row2['facility']."</h6>
                            <h6>Price: ".$row2['price']." Dollars/night.</h6>
                        </div>
                        <div class='col-md-3'>
                            <a href='./booknow.php?roomname=".$row2['roomname']."'><button class='btn btn-primary button'>Book Now</button></a>
                        </div>   
                    </div>
                ";
            }
        } else {
            echo "Error in query: " . mysqli_error($user->db);
        }
    }

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>BookMeHotel</title>


    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
    
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( ".datepicker" ).datepicker({
                  dateFormat : 'yy-mm-dd'
                });
  } );
  </script>
    
    
    <style>
        .well {
            background: rgba(0, 0, 0, 0.7);
            border: none;
            height: 200px;
        }
        
        body {
            background-image: url('images/166015.webp');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;

        }
        
        h4 {
            color: #ffbb2b;
        }
        h6
        {
            color: navajowhite;
            font-family:  monospace;
        }
        label
        {
            color:#ffbb2b;
            font-size: 13px;
            font-weight: 100;
        }
		  .search-form {
        background: rgba(255, 255, 255, 0.8);
        padding: 20px;
        margin-top: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .search-form h3 {
        color: #333;
        margin-bottom: 15px;
    }

    .search-form label {
        display: block;
        margin-bottom: 8px;
        color: #333;
    }

    .search-form input,
    .search-form select,
    .search-form button {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .search-form button {
        background-color: #007BFF;
        color: #fff;
        cursor: pointer;
    }

    .search-form button:hover {
        background-color: #0056b3;
    }

    </style>
    
    
</head>

<body>
    <div class="container">
      
      
       <!-- <img class="img-responsive" src="images/home_banner2.png" style="width:100%; height:180px;">       -->
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <ul class="nav navbar-nav">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="room.php">Room &amp; Facilities</a></li>
                    <li class="active"><a href="reservation.php">Online Reservation</a></li>

                   <li><a href="admin.php">Admin</a></li>
                   <li><a href="about.php">About us</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="http://www.facebook.com"><img src="images/facebook.png"></a></li>
                    <li><a href="http://www.twitter.com"><img src="images/twitter.png"></a></li>                    
                </ul>
            </div>
        </nav>
        
       <div class='row'>
        <div class='col-md-4'></div>
        <div class='col-md-5 well'>
         <form action="" method="post" name="room_category">
              
              
               <div class="form-group">
                    <label for="checkin">Check In :</label>&nbsp;&nbsp;&nbsp;
                    <input type="text" class="datepicker" name="checkin">

                </div>
               
               <div class="form-group">
                    <label for="checkout">Check Out:</label>&nbsp;&nbsp;
                    <input type="text" class="datepicker" name="checkout">
                </div>
                 
               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="submit" class="btn btn-primary button" name="submit">Check Availability</button>

            </form>
           </div>
           <div class="col-md-3"></div>
        </div> 
		 <div class="search-form">
            <form action="" method="post" name="room_search">
                <h3>Search Available Rooms</h3>
                <label for="checkin">Check In:</label>
                <input type="text" class="datepicker" name="checkin">

                <label for="checkout">Check Out:</label>
                <input type="text" class="datepicker" name="checkout">

                <label for="room_category">Room Category:</label>
                <select name="room_category">
                    <option value="all">All Categories</option>
                    <option value="Duplex">Duplex</option>
                    <option value="Family">Family</option>
                    <option value="Super Comfort">Super Comfort</option>
                </select>

                <label for="price">Maximum Price:</label>
                <input type="number" name="price" min="0" step="any">

                <button type="submit" class="btn btn-primary button" name="search">Search</button>
            </form>
        </div>

<?php   
        
         if(isset($_REQUEST[ 'submit']))
         {
            if(mysqli_num_rows($result) > 0)
            {
                while($row = mysqli_fetch_array($result))
                {
                    
                    $room_cat=$row['room_cat'];
                    $sql="SELECT * FROM room_category WHERE roomname='$room_cat'";
                    $query = mysqli_query($user->db, $sql);
                    $row2 = mysqli_fetch_array($query);
                    
                   echo "
                            <div class='row'>
                            <div class='col-md-4'></div>
                            <div class='col-md-5 well'>
                                <h4>".$row2['roomname']."</h4><hr>
                                <h6>No of Beds: ".$row2['no_bed']." ".$row2['bedtype']." bed.</h6>
                                <h6>Available Rooms: ".$row2['available']."</h6>
                                <h6>Facilities: ".$row2['facility']."</h6>
                                <h6>Price: ".$row2['price']." Dollars/night.</h6>
                            </div>
                            <div class='col-md-3'>
                                <a href='./booknow.php?roomname=".$row2['roomname']."'><button class='btn btn-primary button'>Book Now</button></a>
                            </div>   
                            </div>
                             ";
  
                }
           
            }
         }
		 
if (isset($_POST['search'])) {
    // Extract search parameters
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $roomCategory = $_POST['room_category'];
    $maxPrice = isset($_POST['price']) ? $_POST['price'] : PHP_INT_MAX;

    // Validate and sanitize inputs (you might want to add more validation)
    $checkin = mysqli_real_escape_string($user->db, $checkin);
    $checkout = mysqli_real_escape_string($user->db, $checkout);
    $roomCategory = mysqli_real_escape_string($user->db, $roomCategory);

    // Query the database based on search parameters
    $query = "SELECT * FROM room_category WHERE available > 0 AND price <= $maxPrice";

    // Add conditions for checkin, checkout, and room category if provided
    if (!empty($checkin)) {
        $query .= " AND roomname NOT IN (SELECT room_cat FROM rooms WHERE checkout >= '$checkin' AND checkin <= '$checkout')";
    }

    if (!empty($roomCategory) && $roomCategory !== 'all') {
        $query .= " AND roomname = '$roomCategory'";
    }

    // Execute the query
    $result = mysqli_query($user->db, $query);

    // Display the search results
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $room_cat = $row['roomname'];
            $sql = "SELECT * FROM room_category WHERE roomname='$room_cat'";
            $query = mysqli_query($user->db, $sql);
            $row2 = mysqli_fetch_array($query);

            echo "
                <div class='row'>
                    <div class='col-md-4'></div>
                    <div class='col-md-5 well'>
                        <h4>".$row2['roomname']."</h4><hr>
                        <h6>No of Beds: ".$row2['no_bed']." ".$row2['bedtype']." bed.</h6>
                        <h6>Available Rooms: ".$row2['available']."</h6>
                        <h6>Facilities: ".$row2['facility']."</h6>
                        <h6>Price: ".$row2['price']." Dollars/night.</h6>
                    </div>
                    <div class='col-md-3'>
                        <a href='./booknow.php?roomname=".$row2['roomname']."'><button class='btn btn-primary button'>Book Now</button></a>
                    </div>   
                </div>
            ";
        }
    } else {
        echo "Error in query: " . mysqli_error($user->db);
    }
}
        
        
?>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    
    <script src="js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</body>

</html>
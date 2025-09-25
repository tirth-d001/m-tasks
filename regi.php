<?php

  $connect =  mysqli_connect("localhost","root","","registration__db");

  if(isset($_POST['submit'])){
    $fname =$_POST['fname'];
    $lname =$_POST['lname'];
    $email =$_POST['email'];
    $phone =$_POST['phone'];
    $date =$_POST['date'];
    $gender =$_POST['gender'];
    $hobbies = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : '';
    $address =$_POST['address'];
    $country =$_POST['country'];
    
    if(isset($_POST['update_id']) && $_POST['update_id'] != ""){
        $id = intval($_POST['update_id']);
        $sql = "UPDATE users SET 
                    fname='$fname',
                    lname='$lname',
                    email='$email',
                    phone='$phone',
                    date='$date',
                    gender='$gender',
                    hobbies='$hobbies',
                    address='$address',
                    country='$country'
                WHERE id=$id";
    } else {
        // Insert new record
        $sql = "INSERT INTO users (fname,lname,email,phone,date,gender,hobbies,address,country) 
                VALUES ('$fname','$lname','$email','$phone','$date','$gender','$hobbies','$address','$country')";
    }

      if(mysqli_query($connect,$sql))
      {
            header("Location: ".$_SERVER['PHP_SELF']);
        exit();

      }
      else
      {
        echo "error".mysqli_error($connect);
      }

  }


// Check if Edit is clicked
$edit = false;
if(isset($_GET['edit'])){
    $edit = true; // we are in edit mode
    $id = intval($_GET['edit']); // get user ID safely

    // Fetch user data
    $result = mysqli_query($connect, "SELECT * FROM users WHERE id = $id");
    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
    }
}

    if(isset($_GET['delete'])){
      $id = $_GET['delete'];
      mysqli_query($connect,"DELETE FROM users WHERE ID = $id");
      header("Location:".$_SERVER['PHP_SELF']);
      exit();
    }
    
  ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="container">
        <h2><?php echo $edit ? "Edit User" : "Registration Form"; ?></h2>
        <form method="post" action="">
            <label for="fname">First Name</label>
            <input type="text" id="fname" name="fname" placeholder="Enter first name"
                value="<?php echo isset($user['fname']) ? $user['fname'] : ''; ?>" required />

            <label for="lname">Last Name</label>
            <input type="text" id="lname" name="lname" placeholder="Enter last name"
                value="<?php echo isset($user['lname']) ? $user['lname'] : ''; ?>" required />

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter email"
                pattern="^[a-zA-Z0-9._%+-]+@gmail.com$" title="Please enter a valid Gmail address"
                value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required />

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" maxlength="10" minlength="10" placeholder="Enter phone number"
                value="<?php echo isset($user['phone']) ? $user['phone'] : ''; ?>" />

            <label for="date">Birth-date</label>
            <input type="date" id="date" name="date" value="<?php echo isset($user['date']) ? $user['date'] : ''; ?>" required />

            <label class="gender-lable">Gender</label>
            <div class="gender">
                <label style="display:inline;"><input type="radio" name="gender" value="male"
                        <?php if(isset($user['gender']) && $user['gender']=="male") echo "checked"; ?>> Male</label>
                <label style="display:inline;"><input type="radio" name="gender" value="female"
                        <?php if(isset($user['gender']) && $user['gender']=="female") echo "checked"; ?>> Female</label>
                <label style="display:inline;"><input type="radio" name="gender" value="other"
                        <?php if(isset($user['gender']) && $user['gender']=="other") echo "checked"; ?>> Other</label>
            </div>

            <label>Hobbies</label>
            <div class="hobbies">
                <label style="display:inline;"><input type="checkbox" name="hobbies[]" value="reading"
                        <?php if(isset($user['hobbies']) && strpos($user['hobbies'], "reading")!==false) echo "checked"; ?>>
                    Reading</label>
                <label style="display:inline;"><input type="checkbox" name="hobbies[]" value="sports"
                        <?php if(isset($user['hobbies']) && strpos($user['hobbies'], "sports")!==false) echo "checked"; ?>>
                    Sports</label>
                <label style="display:inline;"><input type="checkbox" name="hobbies[]" value="music"
                        <?php if(isset($user['hobbies']) && strpos($user['hobbies'], "music")!==false) echo "checked"; ?>>
                    Music</label>
            </div>

            <label for="address">Address</label>
            <textarea id="address" name="address"
                rows="3"><?php echo isset($user['address']) ? $user['address'] : ''; ?></textarea>

            <label for="country">Country</label>
            <select id="country" name="country">
                <option value="">-- Select Country --</option>
                <option value="india"
                    <?php if(isset($user['country']) && $user['country']=="india") echo "selected"; ?>>India</option>
                <option value="usa" <?php if(isset($user['country']) && $user['country']=="usa") echo "selected"; ?>>USA
                </option>
                <option value="uk" <?php if(isset($user['country']) && $user['country']=="uk") echo "selected"; ?>>UK
                </option>
                <option value="china"
                    <?php if(isset($user['country']) && $user['country']=="china") echo "selected"; ?>>CHINA
                </option>
                <option value="canada"
                    <?php if(isset($user['country']) && $user['country']=="canada") echo "selected"; ?>>Canada</option>
            </select>
            <input type="hidden" name="update_id" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>">
            <button type="submit" name="submit"><?php echo $edit ? "Update" : "Register"; ?></button>
        </form>
    </div>

    <h1 class="udh1">User Data</h1>

    <form method="post" action="" class="search-form">
        <label for="search">Search Users :</label>
        <input type="text" id="search" class="searchbox" name="search" placeholder="Enter name or email"
            value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
        <input type="submit" class="search-btn" name="searchBtn" value="Search">
    </form>

    <?php

   $search = '';
if(isset($_POST['searchBtn'])){
    $search = $_POST['search'];
}

$sql = "SELECT * FROM users WHERE 
        fname LIKE '%$search%' OR 
        lname LIKE '%$search%' OR 
        email LIKE '%$search%'";

$result = mysqli_query($connect, $sql);

    if(mysqli_num_rows($result)>0) { echo '
    <table border="1">
      <tr>
        <th>Fname</th>
        <th>Lname</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Birth-date</th>
        <th>Gender</th>
        <th>Hobbies</th>
        <th>Address</th>
        <th>Country</th>
        <th colspan="2">Action</th>
      </tr>'; 
      while($row = mysqli_fetch_assoc($result)){ 
        echo "<tr>
                  <td>".$row['fname']."</td>
                  <td>".$row['lname']."</td>
                  <td>".$row['email']."</td>
                  <td>".$row['phone']."</td>
                  <td>".$row['date']."</td>
                  <td>".$row['gender']."</td>
                  <td>".$row['hobbies']."</td>
                  <td>".$row['address']."</td>
                  <td>".$row['country']."</td>
                  <td>
                      <a href='?edit=".$row['id']."'>Edit</a>
                  </td>
                  <td>
                      <a href='?delete=".$row['id']."' onclick=\"return confirm('Are you sure you want to delete this user?')\">Delete</a>
                  </td>
      </tr>";
     } 
      echo '</table>'; 
    } 
      else{ echo '<p style="text-align:center; font-weight:bold;">No user found</p>'; }
      
      ?>
</body>

</html>

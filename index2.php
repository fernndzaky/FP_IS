<?php
session_start();
$_SESSION["searching"] = FALSE;
$result = fopen("result2.txt","r");



if(isset($_POST['search'])){
    $_SESSION["movie"] = $_POST['movie'];
    $_SESSION["rating"] = 0;
    $_SESSION["searching"] = TRUE;
}

if(isset($_POST['getRecommendation'])){
    $myfile = fopen("input2.txt", "w") or die("Unable to open file!");
    $movies = $_POST['arr'];
    for ($i=0; $i<count($movies);$i++) {
        fwrite($myfile, $movies[$i]);
        $nl = "\n";
        fwrite($myfile, $nl);
      }
    $command = escapeshellcmd('python ./movie_recommender_collaborative.py');
    $output = shell_exec($command);

    fclose($myfile);
    $fh = fopen("current_list.txt", 'w');
    fwrite($fh,'');

    fclose($fh);
}

    if(isset($_POST['submitRate'])){
        $title = $_POST['title'];
        $rate = $_POST['rate'];
        $_SESSION['searching'] = FALSE;

        #echo $title.','.$rate;
        $myfile = fopen("current_list.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $title.'|'.$rate);
        $nl = "\n";
        fwrite($myfile, $nl);
        fclose($myfile);

        
        }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Collaborative Filtering</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link href='https://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet'>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  
  $( function() {
    var availableTags = [];
    fetch('movie_title2.txt')
    .then(response => response.text())
    .then(text => {
        for(let i = 0;i < text.split("\n").length;i++ ){
            availableTags.push(text.split("\n")[i]);
        //  console.log(text.split("\n")[i])
        }
    }
    )  
    
        
    // availableTags.push("Hola");
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  } );
  </script>
    <style>

.rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

/* Modified from: https://github.com/mukulkant/Star-rating-using-pure-css */
</style>
</head>
<body style="font-family: 'Quicksand'">


    <!-- NAVBAR -->
    <div class="row m-0 p-0">
        <div class="col-sm-12" style="padding: 0px 100px;">
        
            <nav class="navbar navbar-expand-lg navbar-light pl-0">
                <a class="navbar-brand" href="index.php" style="width:15%"><img src="assets/Logo.png" alt="" class="img-fluid"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>

        </div>
    </div>
    <!-- END OF NAVBAR -->

    <!-- SEARCH BAR -->
    
    <div class="row m-0" style="text-align: center !important;">
        <div class="col-sm-12">
            <h1 style="color: #eb6565">Search a movie !</h1>
        </div>
        <div class="col-sm-12" style="padding:20px 100px" >
            <form method="post">
                <input name="movie" id="tags" class="form-control mr-sm-2" type="search" placeholder="Search"  style="width:50%;display: inline-block;" aria-label="Search">
                <input type="submit" name="search" class="btn btn-warning my-2 my-sm-0" style="color: white;" value="Add To List">
            </form>
        </div>
    </div>

    <!-- END OF SEARCH BAR -->
    <?php
        if($_SESSION['searching'])
        {

        
    ?>
    <!-- PICKED LIST -->
    <div class="row m-0">
        <div class="col-sm-12" style="padding:20px 100px">
            <div class="card" >
                <div class="card-header">
                    Rate this movie so we can give you recommendations !
                </div>
                <ul class="list-group list-group-flush">
               
                    <li class="list-group-item">
                
    

                            <form method="post">
                        <p style="display:inline-block;margin-top:10px !important "><?php echo $_SESSION["movie"]?></p>
                        <input type="text" value="<?php echo $_SESSION["movie"] ?>" name="title" hidden>
                
                        <div class="rate" style="display:inline-block;">
                                <input type="radio" id="star5" name="rate" value="5" />
                                <label for="star5" title="text">5 stars</label>
                                <input type="radio" id="star4" name="rate" value="4" />
                                <label for="star4" title="text">4 stars</label>
                                <input type="radio" id="star3" name="rate" value="3" />
                                <label for="star3" title="text">3 stars</label>
                                <input type="radio" id="star2" name="rate" value="2" />
                                <label for="star2" title="text">2 stars</label>
                                <input type="radio" id="star1" name="rate" value="1" />
                                <label for="star1" title="text">1 star</label>
                                <input type="radio" id="star1" name="rate" value="0" checked />
                           
                        </div>
                        <input type="submit" name="submitRate" class="btn btn-warning my-2 my-sm-0" style="color: white;float:right">

                            </form>
              
                    
                    
                    </li>
               

                </ul>
            </div>
        </div>
    </div>
    <!-- END OF PICKED LIST -->
    <?php
    }

    ?>
    <div style="padding: 10px 100px">
        <hr style="background-color: #eb6565;">
    </div>
    <?php 
    if(
        $movies = fopen("current_list.txt","r")){

    
    ?>
    <!-- MOVIE LIST -->
    <div class="row m-0">
        <div class="col-sm-6" style="padding:20px 50px 20px 100px">
            <form method="post">
                <input class="btn btn-warning my-2 my-sm-0" type="submit" name="getRecommendation" value="Get Recommendation !">
        
        </div>
    </div>
    <div class="row m-0">
       
        <div class="col-sm-6" style="padding:20px 50px 20px 100px">
            <div class="card" >
                <div class="card-header">
                    Rated Movie List
                </div>
                <ul class="list-group list-group-flush">
                <?php  
                    
                        /* file empty, error handling */
                    while(! feof($movies))
                    {
                        $z = fgets($movies);
                        if ($z == ''){
                            $z = '';
                        }
                ?>
                    <li class="list-group-item">
                    <?php echo $z ?>
                    <input type="text" name="arr[]" value="<?php echo $z ?>" hidden>
                    </li>
                <?php
                    }
                    fclose($movies);
                ?>    
                    </form>         

            </div>
        </div>
        <?php 
            } 
        ?>
        <!-- END OF RECOMMENDATION LIST -->
        <div class="col-sm-6" style="padding:20px 100px 20px 50px">
            <div class="card" >
                <div class="card-header">
                    Similar Movie List
                </div>
                <ul class="list-group list-group-flush">
                
                <?php  
                    while(! feof($result))
                    {
                        $z = fgets($result);
                ?>
                    <li class="list-group-item"><?php echo $z ?></li>
                <?php
                    }
                    fclose($result);
                ?>
             

                </ul>
            </div>
        </div>
    </div>
    <!-- END OF MOVIE LIST -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
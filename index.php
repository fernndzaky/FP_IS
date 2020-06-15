<?php
$movies = fopen("result.txt","r");

if(isset($_POST['submit'])){
    $myfile = fopen("input.txt", "w") or die("Unable to open file!");
    $movie = $_POST['movie'];
    fwrite($myfile, $movie);
    $nl = "\n";
    fwrite($myfile, $nl);
    fclose($myfile);

    $command = escapeshellcmd('python ./movie_recommender_content_based.py');
    #echo $command;
    $output = shell_exec($command);
    #echo "<pre>$output</pre>";


    // $command = escapeshellcmd('/Applications/XAMPP/xamppfiles/htdocs/php/subwayMap-master/finalproject.py');
    // $command = escapeshellcmd('./finalproject.py');
    // $output = shell_exec($command);
    // $myfile = "result.txt";
    // $result = file($myfile);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Content Based Filtering</title>
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
    fetch('movie_title.txt')
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
            <h1 style="color: #eb6565">Type a Movie you like !</h1>
        </div>
        <div class="col-sm-12" style="padding:20px 100px" >
            <form method="post">
                <input name="movie" id="tags" class="form-control mr-sm-2" type="search" placeholder="Search"  style="width:50%;display: inline-block;" aria-label="Search">
                <input type="submit" name="submit" class="btn btn-warning my-2 my-sm-0" style="color: white;">
            </form>
        </div>
    </div>

    <!-- END OF SEARCH BAR -->
    <div style="padding: 10px 100px">
        <hr style="background-color: #eb6565;">
    </div>
    <!-- MOVIE LIST -->
    <div class="row m-0">
        <div class="col-sm-12" style="padding:20px 100px">
            <div class="card" >
                <div class="card-header">
                    Similar Movie List
                </div>
                <ul class="list-group list-group-flush">
                <?php  
                    while(! feof($movies))
                    {
                        $z = fgets($movies);
                ?>
                    <li class="list-group-item"><?php echo $z ?></li>
                <?php
                    }
                    fclose($movies);
                ?>
                    <!-- <li class="list-group-item">Avatar</li>
                    <li class="list-group-item">Guardians of the Galaxy</li>
                    <li class="list-group-item">Aliens</li>
                    <li class="list-group-item">Star Wars: Clone Wars: Volume 1</li>
                    <li class="list-group-item">Avatar</li>
                    <li class="list-group-item">Guardians of the Galaxy</li>
                    <li class="list-group-item">Aliens</li>
                    <li class="list-group-item">Star Wars: Clone Wars: Volume 1</li> -->
                </ul>
            </div>
        </div>
    </div>
    <!-- END OF MOVIE LIST -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>
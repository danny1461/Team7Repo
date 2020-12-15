<?php
	$this->pageTitle('View Reviews');
?>

<?php
$instructorInfo = $instructor->getProfileModel();
?>
<div class="bg-blue p-5 text-white mb-3">
	<h1 class="mb-0">Reviews for <?= $instructor->getFullName(true) ?></h1>
</div>

<div class="my-3">
	<strong>Note:</strong> <i class="fas fa-check text-success"></i> next to a reviewer name confirms they have been enrolled in a course taught by this instructor.
</div>

<?php   $currentUser = User::getCurrentUser();
        $isStud = User::find("id = :0:", $currentUser->id);
        if ($isStud[0]->type == 'student') {
            echo "<a class = 'btn btn-secondary float-right text-white' href = '".$this->baseUrl("/Student/AddReview/{$instructor->id}")."'>Add Review</a><br>";
        } //If the user is a student, ratings may be added
?>

<?php $reviews = InstructorRatings::find("instructor_id = :0:", $instructor->id);?>


<?php   if($currentUser->type != "admin") { //Allows admin to delete reviews
                for ($counter = count($reviews)-1; $counter >= 0; $counter--) { ?> 
                    <div class = "card"> 
                        <div class = "card-body"> 
                            <?php echo $reviews[$counter]->printRating(); ?>
                            <br>
                        </div>
                    </div>
                    <br>
       <?php }
       } else { 
            for ($counter = count($reviews)-1; $counter >= 0; $counter--) { ?> 
                <div class = "card"> 
                    <div class = "card-body"> 
                        <?php echo $reviews[$counter]->printRating(); ?>
                        <br>
                        <br>
                        <button type="button" class="btn btn-danger" style ="text-align:right"><a href="InstructorController.php?id=<?php echo $rating;?>"> Delete </a></button>
                    </div>
                </div>
                <br>
        <?php } 
    }?>

<?php 
/*
Pervious code before admin code was added:
    for ($counter = count($reviews)-1; $counter >= 0; $counter--) { ?>
    <div class = "card my-3">
        <div class = "card-body">
            <div class="mb-3"><?php echo $reviews[$counter]->printRating(); ?></div>
        </div>
    </div>
    <br>
<?php } */?>

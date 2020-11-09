


<h1 class="class-title"><?php echo $class->class ?></h1>

<form method="POST" id="feedback-form" data-classid="<?php echo $class->classid?>">
	<div class="form-group">
		<label for="feedback-title">Feedback Title</label>
		<input type="text" class="form-control" id="feedback-title" name="feedbacktitle" placeholder="Midterm Feeback">

		<label for="feedback-description">Feedback Description</label>
		<input type="text" class="form-control" id="feedback-description" name="feedbackdescription" placeholder='"Explain to me how you feel about doing a kinesthetic activity for a midterm review"'>
	</div>

	<div class="form-group col-md-6">
		<label for="feedbackstart">Start Time</label>
		<input type="time" class="form-control <?php echo !empty($errors['starttime']) ? 'is-invalid' : '' ?>" id="feedbackstart" name="feedbackstart">
		<label for="feedbackend">End Time</label>
		<input type="time" class="form-control <?php echo !empty($errors['endtime']) ? 'is-invalid' : '' ?>" id="feedbackend" name="feedbackend">
	</div>

	<div>
		<input type="radio" name="type" value="text">Text Feedback <br>
		<input type="radio" name="type" value="rating">Rating Feedback
	</div>
	
	<button type="submit" class="btn btn-primary" id="close-modal">Initiate Feedback Session</button>

</form>

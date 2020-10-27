<h1 class="mb-3" style="background-color: #13284c; padding:60px; color: #ffffff;">Text Feedback Page</h1>


<h1 class="class-title"><?php echo $class->class ?></h1>

<?php if (count($errors)) : ?>
	<ul class="error-list">
		<?php foreach ($errors as $errorMsg) : ?>
			<li><?php echo $errorMsg ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<form method="POST">
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

	<button type="submit" class="btn btn-primary">Initiate Feedback Session</button>

</form>
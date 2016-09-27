<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main <?php echo ( "" != $page_id ? 'id="'.$page_id.'"' : "" ); ?>>

	<section class="container">

		<div class="form-wrapper m-x-auto">

			<header class="animated fadeInDown">
				<h1>Bejelentkezés</h1>
			</header>

			<?php if($this->session->flashdata('message')): ?>
			<div class="alert alert-info alert-dismissible fade in animated fadeInDown m-l-1 m-r-1" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<?php echo $this->session->flashdata('message'); ?>
			</div>
			<?php endif; ?>

			<?php echo form_open(site_url('login'),array('id'=>'loginForm',"class"=>'','method'=>'POST'));?>

			<div class="form-group animated fadeInDown <?php echo (form_error('email')!="" ? 'has-danger' : ''); ?>">
				<?php echo form_label('Email*','email',array("class"=>"control-label"));?>
				<?php if(form_error('email')!=""): ?>
					<?php echo form_input(array('id'=>'email','name'=>'email','class'=>'form-control form-control-danger','aria-describedby'=>'email-input-error-status'),set_value('email')); ?>
				<?php else: ?>
					<?php echo form_input(array('id'=>'email','name'=>'email','class'=>'form-control'),set_value('email')); ?>
				<?php endif; ?>
				<?php if(form_error('email')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('email'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="form-group animated fadeInDown <?php echo (form_error('password')!="" ? 'has-danger' : ''); ?>">
				<?php echo form_label('Jelszó*','password',array("class"=>"control-label"));?>
				<?php if(form_error('password')!=""): ?>
					<?php echo form_password(array('id'=>'password','name'=>'password','class'=>'form-control form-control-danger','aria-describedby'=>'password-input-error-status'),set_value('password')); ?>
				<?php else: ?>
					<?php echo form_password(array('id'=>'password','name'=>'password','class'=>'form-control'),set_value('password')); ?>
				<?php endif; ?>
				<?php if(form_error('password')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('password'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<?php if($captcha): ?>
			<div class="form-group animated fadeInDown row <?php echo (form_error('captcha')!="" ? 'has-danger' : ''); ?>">
				<label for="captcha" class="col-sm-6 col-md-5 col-lg-4 col-form-label">
					<?php echo $captcha['image']; ?>
				</label>
				<?php if(form_error('captcha')!=""): ?>
					<div class="col-sm-6 col-md-7 col-lg-8">
						<?php echo form_input(array('id'=>'captcha','name'=>'captcha','class'=>'form-control form-control-danger','aria-describedby'=>'password-input-error-status'),set_value('captcha')); ?>
					</div>
				<?php else: ?>
					<div class="col-sm-6 col-md-7 col-lg-8">
						<?php echo form_input(array('id'=>'captcha','name'=>'captcha','class'=>'form-control'),set_value('captcha')); ?>
					</div>
				<?php endif; ?>
				<?php if(form_error('captcha')!=""): ?>
					<div class="form-control-feedback col-sm-6 col-md-7 col-lg-8">
						<small><?php echo form_error('captcha'); ?></small>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<div class="form-check animated fadeInDown">
				<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="remeber_me" value="">
					Emlékezz rám.
				</label>
			</div>

			<div class="row animated fadeInDown">
				<div class="col-xs-6">
					<a href="<?php echo site_url('registration'); ?>" class="btn btn-link p-l-0">Regisztráció</a>
				</div>
			</div>

			<div class="text-xs-center animated fadeInDown">
				<button type="submit" class="btn btn-primary">SUBMIT</button>
			</div>

			<?php echo form_close();?>

		</div>
	</section>

</main><!-- ./ Main -->
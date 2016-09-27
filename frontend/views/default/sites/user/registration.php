<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main <?php echo ( "" != $page_id ? 'id="'.$page_id.'"' : "" ); ?>>

	<section class="container">

		<div class="form-wrapper m-x-auto">

			<header class="animated fadeInDown">
				<h1>Regisztáció</h1>
			</header>

			<?php if($this->session->flashdata('message')): ?>
				<div class="alert alert-info alert-dismissible fade in animated fadeInDown m-l-1 m-r-1" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<?php echo $this->session->flashdata('message'); ?>
				</div>
			<?php endif; ?>

			<?php echo form_open(site_url('registration'),array('id'=>'registrationForm',"class"=>'','method'=>'POST'));?>

			<div class="form-group animated fadeInDown <?php echo (form_error('last_name')!="" ? 'has-danger' : ($has_submit ? 'has-success' : '')); ?>">
				<?php echo form_label('Vezetéknév*','lastName',array("class"=>"control-label"));?>
				<?php if(form_error('last_name')!=""): ?>
					<?php echo form_input(array('id'=>'lastName','name'=>'last_name','class'=>'form-control form-control-danger','aria-describedby'=>'last-name-input-error-status'),set_value('last_name')); ?>
				<?php elseif(form_error('last_name')=="" && $has_submit): ?>
					<?php echo form_input(array('id'=>'lastName','name'=>'last_name','class'=>'form-control form-control-success','aria-describedby'=>'last-name-input-success-status'),set_value('last_name')); ?>
				<?php else: ?>
					<?php echo form_input(array('id'=>'lastName','name'=>'last_name','class'=>'form-control'),set_value('last_name')); ?>
				<?php endif; ?>
				<?php if(form_error('last_name')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('last_name'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="form-group animated fadeInDown <?php echo (form_error('first_name')!="" ? 'has-danger' : ($has_submit ? 'has-success' : '')); ?>">
				<?php echo form_label('Keresztnév*','firstName',array("class"=>"control-label"));?>
				<?php if(form_error('first_name')!=""): ?>
					<?php echo form_input(array('id'=>'firstName','name'=>'first_name','class'=>'form-control form-control-danger','aria-describedby'=>'first-name-input-error-status'),set_value('first_name')); ?>
				<?php elseif(form_error('first_name')=="" && $has_submit): ?>
					<?php echo form_input(array('id'=>'firstName','name'=>'first_name','class'=>'form-control form-control-success','aria-describedby'=>'first-name-input-success-status'),set_value('first_name')); ?>
				<?php else: ?>
					<?php echo form_input(array('id'=>'firstName','name'=>'first_name','class'=>'form-control'),set_value('first_name')); ?>
				<?php endif; ?>
				<?php if(form_error('first_name')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('first_name'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="form-group animated fadeInDown <?php echo (form_error('email')!="" ? 'has-danger' : ($has_submit ? 'has-success' : '')); ?>">
				<?php echo form_label('Email*','email',array("class"=>"control-label"));?>
				<?php if(form_error('email')!=""): ?>
					<?php echo form_input(array('id'=>'email','name'=>'email','class'=>'form-control form-control-danger','aria-describedby'=>'email-input-error-status'),set_value('email')); ?>
				<?php elseif(form_error('email')=="" && $has_submit): ?>
					<?php echo form_input(array('id'=>'email','name'=>'email','class'=>'form-control form-control-success','aria-describedby'=>'email-input-success-status'),set_value('email')); ?>
				<?php else: ?>
					<?php echo form_input(array('id'=>'email','name'=>'email','class'=>'form-control'),set_value('email')); ?>
				<?php endif; ?>
				<?php if(form_error('email')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('email'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="form-group animated fadeInDown <?php echo (form_error('password')!="" ? 'has-danger' : ($has_submit ? 'has-success' : '')); ?>">
				<?php echo form_label('Jelszó*','password',array("class"=>"control-label"));?>
				<?php if(form_error('password')!=""): ?>
					<?php echo form_password(array('id'=>'password','name'=>'password','class'=>'form-control form-control-danger','aria-describedby'=>'password-input-error-status'),set_value('password')); ?>
				<?php elseif(form_error('password')=="" && $has_submit): ?>
					<?php echo form_password(array('id'=>'password','name'=>'password','class'=>'form-control form-control-success','aria-describedby'=>'password-input-success-status'),set_value('password')); ?>
				<?php else: ?>
					<?php echo form_password(array('id'=>'password','name'=>'password','class'=>'form-control'),set_value('password')); ?>
				<?php endif; ?>
				<?php if(form_error('password')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('password'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="form-group animated fadeInDown <?php echo (form_error('password_confirm')!="" ? 'has-danger' : ($has_submit ? 'has-success' : '')); ?>">
				<?php echo form_label('Jelszó még egyszer*','passwordConfirm',array("class"=>"control-label"));?>
				<?php if(form_error('password_confirm')!=""): ?>
					<?php echo form_password(array('id'=>'passwordConfirm','name'=>'password_confirm','class'=>'form-control form-control-danger','aria-describedby'=>'password-confirm-input-error-status'),set_value('password_confirm')); ?>
				<?php elseif(form_error('password_confirm')=="" && $has_submit): ?>
					<?php echo form_password(array('id'=>'passwordConfirm','name'=>'password_confirm','class'=>'form-control form-control-success','aria-describedby'=>'password-confirm-input-success-status'),set_value('password_confirm')); ?>
				<?php else: ?>
					<?php echo form_password(array('id'=>'passwordConfirm','name'=>'password_confirm','class'=>'form-control'),set_value('password_confirm')); ?>
				<?php endif; ?>
				<?php if(form_error('password_confirm')!=""): ?>
					<div class="form-control-feedback">
						<small><?php echo form_error('password_confirm'); ?></small>
					</div>
				<?php endif; ?>
			</div>

			<div class="text-xs-center animated fadeInDown">
				<button type="submit" class="btn btn-primary">SUBMIT</button>
			</div>

			<?php echo form_close();?>
		</div>


	</section>

</main><!-- ./ Main -->
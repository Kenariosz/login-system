<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="<?php echo $keys; ?>" />
	<meta name="description" content="<?php echo $description;?>" />
	<title><?php echo $page_title; ?></title>
	<?php echo $this->html_builder->Head->render_style(); ?>
	<?php echo $this->html_builder->Head->render_script(); ?>
	<script type="text/javascript">
		var base_url    = "<?php echo base_url(); ?>";
	</script>
</head>
<body>
<header>
	<?php if($this->authentication_lib->logged_in()): ?>
	<nav class="navbar navbar-light navbar-main" role="navigation">
		<div class="container">
			<a class="navbar-brand navbar-brand animated fadeInLeft" href="#">Navbar</a>
			<div class="display-i pull-md-right">
				<button class="navbar-toggler offcanvas-toggle hidden-md-up animated fadeInRight js-offcanvas-has-events" type="button" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas-main">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="navbar-offcanvas navbar-toggleable-xs" id="js-bootstrap-offcanvas-main">
					<ul class="nav navbar-nav animated fadeInRight">
						<li class="nav-item <?php echo ($page_id == 'home' ? 'active' : ''); ?>">
							<a class="nav-link" href="<?php echo site_url('home'); ?>">Home <?php echo ($page_id == 'home' ? '' : ''); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo site_url('logout'); ?>">Kijelentkezés</a>
						</li>
					</ul>
				</div><!-- /Main content menu -->
			</div>
		</div>
	</nav><!-- /Main nav -->
	<?php endif; ?>
</header>
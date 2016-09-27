<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<main <?php echo ( "" != $page_id ? 'id="'.$page_id.'"' : "" ); ?>>
	<section class="container m-b-2">

		<header>
			<h1 class="animated tada m-t-2 m-b-3">Üdvözöllek újra <?php echo $this->session->userdata('name');?></h1>
		</header>

		<article class="jumbotron animated fadeInRight">
			<p>Ez egy nagyon egyszerű bootstrap 4 alapú landing oldal.</p>
			<p>A menü mobil barát, offcanvas megjelenéssel.</p>
		</article>

	</section>

	<section class="container articles">

		<header>
			<h2 class="animated fadeInLeft m-b-2">Cikkek</h2>
		</header>

		<article class="animated fadeInRight m-b-1">
			<header>
				<h3>Lorem ipsum dolor sit amet</h3>
			</header>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi semper eu lectus sed pretium. Vivamus eleifend mollis eros. Sed euismod urna vitae orci accumsan egestas...</p>
			<footer>
				<dl>
					<dt>Szerző</dt>
					<dd>Kenariosz</dd>
				</dl>
			</footer>
		</article>

		<article class="animated fadeInLeft m-b-1">
			<header>
				<h3>Lorem ipsum dolor sit amet</h3>
			</header>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi semper eu lectus sed pretium. Vivamus eleifend mollis eros. Sed euismod urna vitae orci accumsan egestas...</p>
			<footer>
				<dl>
					<dt>Szerző</dt>
					<dd>Kenariosz</dd>
				</dl>
			</footer>
		</article>

		<article class="animated fadeInRight m-b-1">
			<header>
				<h3>Lorem ipsum dolor sit amet</h3>
			</header>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi semper eu lectus sed pretium. Vivamus eleifend mollis eros. Sed euismod urna vitae orci accumsan egestas...</p>
			<footer>
				<dl>
					<dt>Szerző</dt>
					<dd>Kenariosz</dd>
				</dl>
			</footer>
		</article>

		<article class="animated fadeInLeft m-b-1">
			<header>
				<h3>Lorem ipsum dolor sit amet</h3>
			</header>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi semper eu lectus sed pretium. Vivamus eleifend mollis eros. Sed euismod urna vitae orci accumsan egestas...</p>
			<footer>
				<dl>
					<dt>Szerző</dt>
					<dd>Kenariosz</dd>
				</dl>
			</footer>
		</article>

	</section>

	<footer class="container-fluid">
		<div class="container">
			<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
		</div>
	</footer>
</main>
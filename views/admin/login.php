<?php defined('SYSPATH') or die('No direct script access.');?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Basic Page Needs
	   ================================================== -->
	<meta charset="utf-8" />
	<title><?php echo __($title);?></title>
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- CSS
	   ================================================== -->

<?php echo $media->render_css();?>
	<!--[if IE 7]>
	<link href="/media/admin/css/font-awesome-ie7.css" rel="stylesheet" type="text/css" />
	<![endif]-->

	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="media/ico/favicon.ico">
</head>
<body>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12 content">
			<div id="content">
				<div class="panel login">
					<div class="panel-header">
						<h2><i class="icon-user icon-blue icon-large"></i><?php echo __('Login form')?></h2>
					</div>
					<div class="panel-content">
						<form method="post" id="login-form">
							<fieldset>
								<div id="msg"></div>
								<div id="username-control" class="control-group">
									<div class="input-prepend">
										<span class="add-on"><i class="icon-user"></i></span>
										<input id="username" type="text" name="username" placeholder="<?php echo __('username');?>" >
									</div>
								</div>
								<div id="password-control" class="control-group">

									<div class="input-prepend">
										<span class="add-on"><i class="icon-key"></i></span>
										<input id="password" type="password" name="password" placeholder="<?php echo __('password');?>">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<label class="checkbox">
											<input type="checkbox" name="remember" value="remember"><?php echo __('Remember me');?>
										</label>
									</div>
								</div>
								<hr/>
								<a class="btn btn-large" href="/"><i class="icon-arrow-left icon-blue"></i><?php echo __('Back to index')?></a>
								<button class="btn btn-primary btn-large stateful" type="submit" data-loading-text="<?php echo __('Login')?>..."><i class="icon-ok icon-white"></i><?php echo __('Login')?></button>
							</fieldset>
						</form>
					</div>
				</div><!--/span-->
			</div><!-- content -->
		</div>
	</div><!--/row-->
	<hr>
	<footer>
		<p>Copyright &copy;  2012 Liu.Jie</p>
	</footer>
</div><!--/.fluid-container-->
</body>

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<?php echo $media->render_js();?>

</html>
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
	<title><?php echo $title;?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!--[if IE 7]>
	<link href="<?php echo URL::site('/media/admin/css/font-awesome-ie7.min.css');?>" rel="stylesheet" type="text/css" />
	<![endif]-->

	<!-- CSS
	   ================================================== -->
	<?php echo $media->render_css();?>

	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="<?php echo URL::site('/media/admin/ico/favicon.ico');?>">
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" target="_blank" href="<?php echo URL::site('/');?>">Comasa</a>
			<div class="btn-group pull-right">
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-user"></i> <?php echo $user->username;?>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo URL::site('/admin/user/edit/'.$user->pk());?>"><i class="icon-user"></i>个人资料</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo Route::url('admin/auth', array('action'=>'logout'));?>"><i class="icon-off"></i><?php echo __('Sign Out');?></a></li>
				</ul>
			</div>

			<div class="btn-group pull-right">
				<a class="badge badge-warning" data-toggle="dropdown">77</a>
				<ul class="dropdown-menu popup">
					<li>
						<a href="#"><i class="icon-comment icon-blue"></i>New content
							<span class="popup-content">
								Title: New article<br/>
								Author: Leonardo
							</span>
						</a>
					</li>
					<li>
						<a href="#"><i class="icon-comment icon-blue"></i>New content
							<span class="popup-content">
								Title: Nice admin theme<br/>
								Author: Lonardo
							</span>
						</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="#"><i class="icon-user icon-blue"></i>New user
							<span class="popup-content">
								Username: Lonardo
							</span>
						</a>
					</li>
					<li>
						<a href="#"><i class="icon-user icon-green"></i>New user
							<span class="popup-content">
								Username: Tom
							</span></a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="#"><i class="icon-picture icon-blue"></i>New picture
							<span class="popup-content">
								Username: Lonardo
								<!--<img src="images/cms-theme.png" alt="Admin theme" />-->
							</span>
						</a>
					</li>
					<li>
						<a href="#"><i class="icon-picture icon-orange"></i>New picture
							<span class="popup-content">
								Username: Tom
							</span>
						</a>
					</li>
				</ul>
			</div>
			<div class="nav-collapse">
				<ul class="nav">
					<!--//只要是管理员都可以查看首页信息-->
					<li <?php echo Request::$current->controller() == 'home' ? 'class="active"':'';?>><a href="<?php echo Route::url('admin');?>"><i class="icon-home icon-aqua"></i> <?php echo __('Home')?></a></li>

                    <?php
                        $top_nav = Arr::get($nav, 'top_nav', array());
                        foreach($top_nav as $key => $value):?>
                        <?php $controller = Arr::get($value, 'controller', $key);?>

                        <?php if(isset($value['sub_nav']) AND is_array($value['sub_nav'])):?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="<?php echo isset($value['icon'])?$value['icon']:'icon-'.$key?> icon-aqua"></i> <?php echo isset($value['name'])?__($value['name']):__(ucfirst($key))?>
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php foreach($value['sub_nav'] as $sub_key => $sub_nav):?>
                                    <li><a href="<?php echo Route::url('admin', array('controller' => $controller, 'action' => $sub_key));?>"><i class="<?php echo $sub_nav['icon'];?>"></i> <?php echo isset($sub_nav['name'])?__($sub_nav['name']):__(ucfirst($sub_key))?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </li>
                            <?php else :?>
                            <li <?php echo Request::$current->controller() == $controller ? 'class="active"':'';?>><a href="<?php echo Route::url('admin', array('controller'=>$controller))?>"><i class="<?php echo isset($value['icon'])?$value['icon']:'icon-'.$key?> icon-aqua"></i><?php echo isset($value['name'])?__($value['name']):__(ucfirst($key))?></a></li>
                        <?php endif;?>
                    <?php endforeach;?>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-random icon-aqua"></i> <?php echo __('Shortcuts');?>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?php echo Route::url('admin', array('controller' => 'article', 'action' => 'create'));?>"><i class="icon-edit"></i> Create article</a></li>
							<li><a href="#"><i class="icon-plus"></i> Add user</a></li>
							<li class="divider"></li>
							<li><a href="#"><i class="icon-comment"></i> Comments</a></li>
							<li><a href="#"><i class="icon-user"></i> users</a></li>
						</ul>
					</li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span2 sidebar-nav">
			<ul class="nav nav-list">
				<li <?php echo Request::$current->controller() == 'home' ? 'class="active"':'';?>><a href="<?php echo Route::url('admin');?>"><i class="icon-home icon-aqua"></i> <?php echo __('Home');?></a></li>
				<?php
				$side_nav = Arr::get($nav, 'side_nav', array());
				foreach($side_nav as $key => $value):
				$controller = Arr::get($value, 'controller', $key);
				?>
				<li <?php echo Request::$current->controller() == $controller ? 'class="active"':'';?>><a href="<?php echo Route::url('admin', array('controller' => $controller));?>"><i class="<?php echo Arr::get($value, 'icon', 'icon-'.$key);?> icon-aqua"></i> <?php echo __(ucfirst($key));?></a></li>
				<?php endforeach;?>
				<li class="nav-header">Another menu</li>
				<li><a><i class="icon-user icon-aqua"></i> Login form</a></li>
			</ul>
		</div>

		<!-- //progress -->
		<div id="ajaxstatus" class="ajaxstatus"><div class="progress progress-striped active hide"><div class="bar" style="width: 25%;"></div></div></div>

		<div id="content" class="span10 main">
			<?php echo $content;?>
		</div><!-- //main -->
	</div><!--//row-->

</div><!--/.fluid-container-->
<footer class="footer">
    <div class="container">
        <p>Copyright &copy;  2012 - <?php echo date('Y');?> Liu.Jie</p>
    </div>
</footer>

<!--[if lt IE 9]>
<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!-- JS
================================================== -->
<script type="text/javascript">
	var UPLOAD_URL = "<?php echo URL::site('/editor/upload');?>";
	var FILE_MANAGE_URL = "<?php echo URL::site('/editor/manage');?>";
</script>
<?php echo $media->render_js();?>
</body>
</html>
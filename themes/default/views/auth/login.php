<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); 

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="shortcut icon" href="<?=base_url();?>/icon.ico"/>
<script type="text/javascript">if (parent.frames.length !== 0) { top.location = '<?=site_url('login')?>'; }</script>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/iCheck/square/green.css" rel="stylesheet" type="text/css" />
<style>
body{padding-top:120px;padding-bottom:40px;background-color:#eee}
.btn{outline:0;border:none;border-top:none;border-bottom:none;border-left:none;border-right:none;box-shadow:inset 2px -3px rgba(0,0,0,0.15)}
.btn:focus{outline:0;-webkit-outline:0;-moz-outline:0}
.fullscreen_bg{position:fixed;top:0;right:0;bottom:0;left:0;background-size:cover;background-position:50% 50%;background-image:url(<?=base_url();?>fundo.jpg);background-repeat:repeat}
form {max-width:280px;padding:15px;margin:0 auto;margin-top:50px}
form .form-signin-heading,.form-signin{margin-bottom:10px}
form .form-control{position:relative;font-size:16px;margin:10px 0px;height:auto;padding:10px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box}
form .form-control:focus{z-index:2}
form input[type="text"]{margin-bottom:-1px;border-radius:4px;border-color:#000;border-style:solid solid none}
form input[type="password"]{margin-bottom:10px;border-radius:4px;border-color:#000;border-top:1px solid rgba(0,0,0,0.08);border-style:none solid solid}
.form-signin-heading{color:#fff;text-align:center;text-shadow:0 2px 2px rgba(0,0,0,0.5)}
.tudonet-logo{font-size: 80px; font-weight: bold; letter-spacing: -9px;}
</style>
</head>

<div id="fullscreen_bg" class="fullscreen_bg"/>

<div class="container">
<?= form_open("auth/login"); ?>"
		<h1 class="form-signin-heading text-muted tudonet-logo"><img src="<?=base_url();?>/logo.png" style="width:100%;max-height:80px;"></h1>
        <h1 class="form-signin-heading text-muted">PDV NF-e</h1>
		<?php 
		if(isset($_GET["err"]) && $_GET["err"]=="1"){ ?>
			<b style="color:red">Erro no e-mail ou senha, verifique as credenciais e tente novamente.</b>
		<?php } ?>
		<input type="text" class="form-control" placeholder="Seu email" name="identity" required="" autofocus="">
		<input type="password" class="form-control" placeholder="Sua senha" name="password" required="">
		<button class="btn btn-lg btn-primary btn-block" type="submit">
			Entrar no sistema
		</button>
	</form>

</div>

</body>
</html>

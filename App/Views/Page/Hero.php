<style type="text/css">
.breadcrumbs:before {
	content: '';
	background: rgba(80 0 0 / 70%);
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
}
.breadcrumbs {
	padding-top: 160px;
	padding-bottom: 120px;
	z-index: 2;
	text-align: left;
	background-color: #24126A;
	background-image: url("<?=ROOT.BANNER2_IMAGE?>");
	background-size: cover;
	background-position: right;
	background-repeat: no-repeat;
	-webkit-box-shadow: 0px 7px 30px rgba(0, 0, 0, 0.075);
	box-shadow: 0px 7px 30px rgba(0, 0, 0, 0.075);
	color: var(--color-white);
}
</style>

<!-- Hero -->
<div class="section breadcrumbs mb-5">
	<div class="container mw-400 mx-auto text-center">
		<h3><?=ucfirst(str_replace("-", " ", $mode))?></h3>
		<p><a href="<?=ROOT?>/">Home</a> <i class="fas fa-caret-right mx-3"></i> <span><?=ucfirst(str_replace("-", " ", $mode))?></span></p>
	</div>
</div>
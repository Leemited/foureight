
<script type="text/javascript" src="<?php echo G5_JS_URL; ?>/jquery.accordion.js"></script><!--아코디언-->
<script type="text/javascript">
	$('.accordion').accordion({
		"transitionSpeed": 400
	});
</script>

<!-- <object id="secmgr" viewastext style="display:none" classid="clsid:5445be81-b796-11d2-b931-002018654e2e"
codebase="http://xn--2n1bv5nmzb22lttdtsm.com/smsx.cab#Version=7,7,0,20">
<param name="GUID" value="{2673ED0C-4340-4B15-A431-80A947E76481}">
<param name="Path" value="http://licenses.meadroid.com/download/{2673ED0C-4340-4B15-A431-80A947E76481}/mlf">
<param name="Revision" value="0">
</object>

<object id="factory" viewastext style="display:none" classid = "clsid:1663ed61-23eb-11d2-b92f-008048fdd814">
</object> -->

<!-- 
<object id="factory" viewastext style="display:none" classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814" codebase="http://xn--2n1bv5nmzb22lttdtsm.com/smsx.cab#Version=7,7,0,20"></object>

<script>
$(window).on("load",function(){
	if (MeadCo.ScriptX.Init()) {
		MeadCo.ScriptX.Printing.header = "비전아카데미";
		MeadCo.ScriptX.Printing.footer = "비전아카데미";
		MeadCo.ScriptX.Printing.orientation = "landscape";
				
		// link the ui ...
		$("#printBtn").click(function (e) { 
			$(".no_print").css("display","none");
			e.preventDefault(); 
			MeadCo.ScriptX.PrintPage(false); 
			$(".no_print").css("display","block");
		});                           
	 }
});
function fnPrint(){
	$(".no_print").css("display","none");
	window.print();
	$(".no_print").css("display","block");
}
</script>
 -->
<?php 
include_once(G5_PATH."/tail.sub.php");
?>


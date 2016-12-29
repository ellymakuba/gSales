<?PHP
	require 'data_access_object.php';
	$dao=new DAO();
	$dao->checkLogin();
	$pageSecurity=5;
	if (isset($_POST['create'])){
	}
  ?>
  <html>
  <?PHP $dao->includeHead('Product List',0) ?>
	<script>
	$(document).ready(function(){
		$.ajax({
		 type:"GET",
		 url:"calendar_sales.php",
		 async:false,
		 success:function(data){
			 var calendar={};
			 data=JSON.parse(data);
			 for(var i=0; i<data.length; i++){
				 var d=data[i].date;
				 calendar[d]={"number":"KSH."+data[i].sales};
			 }
			 var currentdate = new Date();
	 		var date =currentdate.getFullYear()+"-"+(currentdate.getMonth()+1);
	 		$(".responsive-calendar").responsiveCalendar({
	 			time: date,
	 			events: calendar
	 		});
		 }
	 });
	});
	</script>
  </head>
  <body class="container">
  <?PHP $dao->includeMenu($_SESSION['tab_no']);
	 if(in_array($pageSecurity, $_SESSION['AllowedPageSecurityTokens'])){?>
		    	<div class="responsive-calendar">
						<h2 class="form-signin-heading">Daily Sales</h2>
		        <div class="controls">
		            <a class="pull-left" data-go="prev"><div class="btn btn-primary">Prev</div></a>
		            <h4><span data-head-year></span> <span data-head-month></span></h4>
		            <a class="pull-right" data-go="next"><div class="btn btn-primary">Next</div></a>
		        </div><hr/>
		        <div class="day-headers">
		          <div class="day header">Mon</div>
		          <div class="day header">Tue</div>
		          <div class="day header">Wed</div>
		          <div class="day header">Thu</div>
		          <div class="day header">Fri</div>
		          <div class="day header">Sat</div>
		          <div class="day header">Sun</div>
		        </div>
		        <div class="days" data-group="days">
		        </div>
		      </div>
		      <!-- Responsive calendar - END -->
		    </div>
		<?php }
		else{
			echo '<div class="alert alert-danger">
				<strong>You do not have permission to access this page, please confirm with the system administrator</strong>
			</div>';
		}
		require 'footer.php';?>
  </body>
  </html>

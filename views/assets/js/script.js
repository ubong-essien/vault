function rootname(){
	var HOST = $(location).attr('hostname');
	var protocol = $(location).attr('protocol');
	var page = $(location).attr('pathname');
	var d = page.split('/');
	var root = protocol + "//" + HOST + "/" + d[1] + "/";
	
	return root;
  }


 $(document).ready(function(){
	
	 $('#cgrp').change(function(e){
        e.preventDefault();
		 var r=$('#cgrp').val();
		//alert(r);
       // return;
		 e.preventDefault();
		     $.ajax({ 
                type:'POST',
				url:'pages/loadCG/' + r,
				success:function(html){
				$('#dpt_tile').html(html)	
									}
				
            });  
		});
        /************************************************ */

        $('#pass-form').submit(function(e){
            e.preventDefault();
            var r=$('#pass-form').serialize();
            console.log(r);
            e.preventDefault();
                $.post({ 
                   type:'POST',
                   url:rootname()+'public/secure/auth',
                   data:r,
                   success:function(response){
                    var obj = jQuery.parseJSON(response);

                    console.log( obj.Message.Status);
                    console.log( obj.Message.text);
                    // console.log( obj.responsedata.user_name);
                    // console.log( obj.responsedata.rf_token);
                    console.log("Accessing Vault Resources!!");

                    

                    if((obj.Message.Status == 'SUCCESS') && (obj.Message.text == '###')){
                        $('#stg').html("<span class='alert alert-success'>Authentiction Successfull,Accessing Vault Resources!!</span>");
                        // var geturl = window.location;
                        // var baseurl = geturl.protocol + "//"+geturl.host +"/" + geturl.pathname.split('/')[1];
                        var url = rootname()+ "public/secure/show_token/"+ obj.responsedata.rf_token;
                        $(location).attr('href',url);  
                        $('#name').html(obj.responsedata.user_name)
                   }else{
                        $('#stg').html("<p class='alert alert-danger'>Authentication Failed...</p>");
                   }

                }
                   
               });  
           });
           /************************************ */
           $('#course_g').submit(function(e){
            e.preventDefault();
           
            var r=$('#course_g').serialize();
           //alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'admin/process_CG/',
                   data:r,
                   success:function(html){
                       console.log(html);
                   $('#stge').html(html)	
                                       }
                   
               });  
           });
           /**************************************************** */
           $('#examiner_create').submit(function(e){
            e.preventDefault();
           
            var r=$('#examiner_create').serialize();
           //alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'admin/process_examiner_form/',
                   data:r,
                   success:function(html){
                       console.log(html);
                   $('#stage').html(html)	
                                       }
                   
               });  
           });
           /*********************************************** */
           $('#create_user').submit(function(e){
            e.preventDefault();
           
            var r=$('#create_user').serialize();
           //alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'admin/process_user/',
                   data:r,
                   success:function(html){
                       console.log(html);
                   $('#staged').html(html)	
                                       }
                   
               });  
           });
           
        /************************************************************** */
        $('#settings_form').submit(function(e){
            e.preventDefault();
           
            var r=$('#settings_form').serialize();
          // alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'admin/process_settings/',
                   data:r,
                   success:function(html){
                       console.log(html);
                
                   setTimeout(location.reload(true),3000);
                   delay(2000);
                   $('#st').html(html);
                                       }
                   
               });  
           });

               /************************************************************** */
        $('#attnd').submit(function(e){
            e.preventDefault();
          
            var r=$('#attnd').serialize();
           //alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'report/GenerateAttendance/',
                   data:r,
                   success:function(html){
                       console.log(html);
                   $('#tb_stage').html(html);
                                       }
                   
               });  
           });
           /***************** ***************/
            //print div
   $('#exms').change(function(e){
        e.preventDefault();
        var selected = $('#exms').val();
           $('#print_link').attr('href','print_attendance/'+ selected);
         //   alert(selected);
   });
 /************************************************************** */
 //print examscores
   $('#exmsc').change(function(e){
        e.preventDefault();
        var selected = $('#exmsc').val();
           $('#print_linksc').attr('href','print_scores/'+ selected);
           // alert(selected);
   });
    /************************************************************** */
 //print resultcores
 $('#dpt').change(function(e){
    e.preventDefault();
    var exams_det = $('#exmrs').val();
    var deptm = $('#dpt').val();
    var selected = exams_det + ":" + deptm;
       $('#print_linkres').attr('href','print_result/'+ selected);
       // alert(selected);


      

});
/*************************************************************** */
 $('#select-all').click(function() {   
        //alert('clicked');
        if(this.checked) {
            // Iterate each checkbox
            $(':checkbox').each(function() {
                this.checked = true;                        
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;                       
            });
        }
    });

 /**************************************** */
        $('#scores').submit(function(e){
            e.preventDefault();
          
            var f=$('#scores').serialize();
           //alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'report/GenerateScores/',
                   data:f,
                   success:function(html){
                       console.log(html);
                   $('#scoretb_stage').html(html);
                                       }
                   
               });  
           });
/*************************************************** */
$('#server_count').change(function(e){
   
    e.preventDefault();
    var server_count =$('#server_count').val();
   //alert('change has come' + server_count);return;
    
   //alert(r);
   //return;
    e.preventDefault();
        $.ajax({ 
           type:'POST',
           url:'admin/create_server_config_form/',
           data:{server_count:server_count},
           success:function(html){
              // console.log(html);
           $('#server_stage').html(html);
                               }
           
       });  
   });
/*************************************************** */
$('#server').submit(function(e){
   //alert('reee');
    e.preventDefault();
    var servers_info=$('#server').serialize();
   //alert('change has come' + server_count);return;
    console.log(servers_info);
   //alert(r);
   //return;
   // e.preventDefault();
        $.ajax({ 
           type:'POST',
           url:'admin/process_server_form/',
           data:servers_info,
           success:function(html){
               console.log(html);
               //return;
           $('#stge').html(html);
                               }
           
       });  
   });

   /*************************************************** */
$('#server_connect').submit(function(e){
    //alert('reee');
     e.preventDefault();
     var serversinfo=$('#server_connect').serialize();
    //alert('change has come' + server_count);return;
     console.log(serversinfo);
    //alert(r);
    //return;
    // e.preventDefault();
         $.ajax({ 
            type:'POST',
            url:'admin/process_connect/',
            data:serversinfo,
            success:function(html){
                console.log(html);
                //return;
            $('#stge_con').html(html);
                                }
            
        });  
    });
/*************************************************** */


  $('#result').submit(function(e){
            e.preventDefault();
          
            var f=$('#result').serialize();
           //alert(r);
           //return;
            e.preventDefault();
                $.ajax({ 
                   type:'POST',
                   url:'result/GenerateResult/',
                   data:f,
                   success:function(html){
                       console.log(html);
                   $('#resulttb_stage').html(html);
                //    var tot = $('#tot').html();
                //    alert(tot);
                //    $('#tot_score').html(tot);
                                       }
                   
               });  
           });
 });  
  function p(){
           $('#print_link').hide();
           window.print();
       }
 function setactive(id){
//alert(id);
//return;
    var res = '';
        $.ajax({ 
           type:'POST',
           url:'admin/setActive/',
           data:{exid:id},
           success:function(html){
               console.log(html);
            res = html;
           setTimeout(location.reload(true),1000);
             //$('#acti').html(res);
                               }
           
       });

       }
    
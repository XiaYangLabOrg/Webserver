function SSEAreview() //This function gets the review table for SSEA
                {
                    

                        $.ajax({
                                    url:"SSEA_moduleprogress.php",
                                    method:"GET",
                                    data:{
                                        randomstr : string
                                    },
                                    success:function(data) {
                                        $('#mySSEA_review').html(data);
                                    }
                                });
                        $('#SSEAtab2').show();
                        $('#SSEAtab2').click();
                        
                     

                     
                }


///////////////Start Submit Function (SSEA form) -- Function for clicking 'Click to review button'///////////////////////////////////
                    
   $('#SSEAdataform').submit(function(){
                         

                           
                var choice_SSEA = $("select[name='formChoice_SSEA'] option:selected").val(),
                choice2_SSEA = $("select[name='formChoice2_SSEA'] option:selected").val(),
                select_SSEA = $("select[name='formChoice_SSEA'] option:selected").index(),
                 select2_SSEA = $("select[name='formChoice2_SSEA'] option:selected").index(),
                permutype_SSEA = $('select[name="permuttype"] option:selected').val(),
                maxgene_SSEA = $('input[name="maxgene"]').val(),
                 mingene_SSEA = $('input[name="mingene"]').val(),
                maxoverlap_SSEA = $('input[name="gene_overlap"]').val(),
                 minoverlap_SSEA = $('input[name="overlap"]').val(),
                permu_SSEA = $('input[name="permu"]').val(),
                sseafdr_SSEA = $('input[name="sseafdr"]').val();

                var string = "<?php echo $random_string; ?>"; 
                
                
                alert(choice_SSEA + "<br>" + choice2_SSEA + "<br>" + select_SSEA + "<br>" + select2_SSEA + "<br>" + permutype_SSEA + "<br>" + maxgene_SSEA + "<br>" + mingene_SSEA + "<br>" + maxoverlap_SSEA + "<br>" + minoverlap_SSEA + "<br>" + permu_SSEA +  "<br>" + sseafdr_SSEA + "<br>" + string);
                

                
            
               

                return false;

              
                     });
/////////////////////////////////////////////End submit function for SSEA form//////////////////////////////////////////////////////



///////////////Start Validation/REVIEW button -- Function for clicking 'Click to review button'///////////////////////////////////
                 $("#Validatebutton_SSEA").on('click', function() {

                            var select = $("select[name='formChoice_SSEA'] option:selected").index(),
                            select2 = $("select[name='formChoice2_SSEA'] option:selected").index(),

                            
                            var selectarray = [select, select2];
                            var idarray = ['GSETuploadInput', 'GSETDuploadInput'];
                            var errorlist = [];
                            selectarray.forEach(myFunction);

                            function myFunction(item, index) 
                            {
                                
                                if(item === 0)
                                {
                                    errorlist.push($('#' + idarray[index].toString()).parent().attr('name') + ' is not selected!');
                                }
                                else if(item === 1)
                                {
                                    if($('#' + idarray[index].toString()).val() == '')
                                    {
                                        errorlist.push($('#' + idarray[index].toString()).parent().attr('name') + ' is not selected!');
                                    }
                                }
                                else
                                {

                                }
                                
                                
                            }


                                $('input[type="text"]').each(function(){
                                   if($(this).val()==""){
                                      errorlist.push($(this).parent().parent().attr('name') + ' is empty!');
                                    }
                                 });
                            
                            if (errorlist.length === 0) 
                            {
                                //$(this).html('Please wait ...')
                                //.attr('disabled','disabled');
                                 $("#SSEAdataform").submit();
                            }
                            else
                            {
                                var result = errorlist.join("\n");
                                //alert(result);
                                $('#errorp_SSEA').html(result);
                                $("#errormsg_SSEA").fadeTo(2000, 500).slideUp(500, function()
                                {
                                    $("#errormsg_SSEA").slideUp(500);
                                 });


                            }
                   
                           
              


            });


 ///////////////////////////////////////////////End Validation/REVIEW button/////////////////////////////////////////////////////////////


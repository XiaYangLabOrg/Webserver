<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<style>
  .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 200; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  /*text-align: center; */
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 60%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
input[type="checkbox"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    -ms-appearance: none;
    -o-appearance: none;
    appearance: none;
    position: relative;
    height: 15px;
    width: 15px;
    border-radius: 5px;
    background: #cbd1d8;
    border: none;
    color: #fff;
    cursor: pointer;
    display: inline-block;
    z-index: 1000;
}

input[type="checkbox"]:checked::after {
    background: #39a9a4;
    height: 15px;
    width: 15px;
    border-radius: 4px;
    border: 1px solid black;
    content: "\2713";
    font-size: 10px;
    display: block;
    z-index: 100;
    text-align: center;
    box-shadow: 2px 1px 6px -6px #555;
}
</style>

<div id="myModal" class="modal">
<!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <article style="text-align: center;"> 
      <h1>We&rsquo;ll be back soon!</h1>
      <div>
        <p>Sorry for the inconvenience but we&rsquo;re performing maintenance at 5:00 a.m. on Monday, September 18, 2023 through 6:00 p.m. on Wednesday, September 20, 2023 (PST). We&rsquo;ll be back online shortly!</p>
        <p>&mdash; Mergeomics & Pharmomics Team</p>

        <div class="">

            <input id="checkbox" type="checkbox"> Don't show this again!

        </div>
      </div>
    </article>
  </div>
</div>

   

  <!-- External JavaScripts IMPORTANT!
  ============================================= -->
  <script src="include/js/jquery.js"></script>
  <script src="include/js/plugins.js"></script>

  <!-- Footer Scripts IMPORTANT!
  ============================================= -->
  <script src="include/js/functions.js"></script>
  <script type="text/javascript">
    // Get the modal

  var modal = document.getElementById("myModal");

  // Get the button that opens the modal
  var btn = document.getElementById("myBtn");

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];




  var dontshow=sessionStorage.getItem("dontshow");
  // unmask this for maintenance notification -Dan
  if(dontshow== null || dontshow=="null"){
    modal.style.display="block";
  }

  //Change the key value to something else for next maintenance notification.
  $('#checkbox').click(function(){
      if ($('#checkbox').prop('checked')) {

        sessionStorage.setItem("dontshow", "True");
      }else{
        sessionStorage.setItem("dontshow", null);
      }
  }) 
  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
  </script>

</html>
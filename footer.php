<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/header.js"></script>

<script>
function aramaYap(){  
    var ara = document.getElementById("ara").value;  
    window.location.href="?s=" + ara;
}  

var input = document.getElementById("ara");
input.addEventListener("keyup", function(event) {
if (event.keyCode === 13) {
event.preventDefault();
aramaYap();
}
});
</script>
</body>
</html>
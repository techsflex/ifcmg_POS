<!DOCTYPE html>
<html>
<head><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script></head>
<body>

<p>Click the button to trigger a function that will output "Hello World" in a p element with id="demo".</p>
<input type="text" class="qtyTextBox">
<button   id="1">Click me</button>

<p id="demo"></p>


<script>


			$('.qtyTextBox').change(function() {
                   alert($(this).val());
               });

</script>

</body>
</html>

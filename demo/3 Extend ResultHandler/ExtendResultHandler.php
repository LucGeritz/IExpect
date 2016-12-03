<?php
include('../../vendor/autoload.php');


class WebResultHandler extends Tigrez\IExpect\ResultHandler{
		
		protected function show($ok, Tigrez\IExpect\Caller $caller, $extra=''){
			
			$prefix = $this->prefix($caller);
		
			if($ok){
				$msg = $prefix . ' OK';
				$class = "pass";
			}
			else{
				$line = $this->getSrcLine($caller);
				$msg = $prefix . " NOK **** ".trim($line);
				$class="fail";
			}
	
			echo '<li class="'.$class.'">'.$msg." $extra</li>";
		
		}
			
}

$I = new Tigrez\IExpect\Assertion();
$I -> setResultHandler(new WebResultHandler());

?>
<!DOCTYPE html>
<html>
<style>
	.pass{
		color: green;
	}
	.fail{
		color: crimson;
	}
</style>	
<body>
<h1>Extend Resulthandler Demo</h1>
<p>
A demo on how you can change your own ResultHandler functionality.
The default output is done by <code>ResultHandler::show()</code> and is aimed at the
console. Now I want the output to end up in the browser so it
needs HTML makeup. <br>
I choose to extend ResultHandler instead of a new class implementing <code>IResultHandler</code>. 
I do this because I do want to reuse the functionality in ResultHandler and only
override the <code>show()</code> method.
</p>

<ul>
<?php
	$I -> expect([])->not()->isTruthy();
	$I -> expect([3,4,'no'])->hasValue('no');
	$I -> expect('4')->equals(4);
	$I -> expect('Unit testing is fun?')->caseInsensitive()->contains('unit');
?>
</ul>
</body>
</html>
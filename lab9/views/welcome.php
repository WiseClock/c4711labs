<p class="lead">
    <a href="/order/neworder" class="btn btn-large btn-primary">Start a new order</a>    
    <a href="/admin" class="btn btn-large btn-danger">Secret Admin Stuff</a>
</p>

<form action="xx.php">
Username:<br>
<input type="text" name="username" >
<br>
Password:<br>
<input type="text" name="password" >
<br><br>
<input type="submit" value="Submit">
</form> 


<p>Order summary:</p>
<table class="table">
    <tr>
        <th>Order #</th>
        <th>Date/Time</th>
        <th>Amount</th>
    </tr>
    {orders}
    <tr>
        <td>{num}</td>
        <td>{datetime}</td>
        <td>{amount}</td>
    </tr>
    {/orders}
</table>
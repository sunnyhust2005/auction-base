<?php
include ('./sqlitedb.php');	
?>

<?php
try {
	$itemQuery = 'select sellerID, rating, description, Item.location, Item.country, firstBid, started, ends from Item NATURAL JOIN Seller where itemID = '. $_REQUEST["itemID"];	
	
	$itemResult = $db->query($itemQuery);
	$item = $itemResult->fetch();

	echo '<p><strong>Seller </strong> '.$item["sellerID"].'<strong>    Rating</strong>  '. $item["rating"] .'</p>';
	echo '<p><strong>Description  </strong> '.$item["description"].'</p>';
	echo '<p><strong>Location </strong> '.$item["location"].', '.$item["country"].'</p>';
	echo '<p><strong>Started on  </strong>'.$item["started"].'</p>';
	$isBiddingOpen = $_REQUEST["isBiddingOpen"] == "true";
	$endsTitle = ($isBiddingOpen) ? "Ends on" : "Ended on";
	echo '<p><strong>'.$endsTitle.'  </strong>'.$item["ends"].'</p>';
	if ($isBiddingOpen) {
		echo '<p><strong>Starting Bid </strong>'.money_format('$%i', floatval($item["firstBid"])).'</p>';
		echo '<form class="well form-inline" id="bid-form" action="#" method="post">
			<span class="input-prepend input-append"><span class="add-on">$</span><input name="bid" id="bid" class="span2" type="text" size="80" placeholder="Your Bid" /><span class="add-on">.00</span>&nbsp;&nbsp;&nbsp;&nbsp;
	    <button type="submit" class="btn">Bid Now</button>
		</span>
	    </form>';
	}
	else {
		echo '<p>Bidding is closed. </p>';
	}
} catch (PDOException $e) {
	echo "Bid item query failed: " . $e->getMessage();
}
?>
<div id="history-table"></div>
<script type="text/javascript">
	$('#history-table').load('history-table.php', { "itemID" : <?php echo $_REQUEST["itemID"] ?>, "numBids" : <?php echo $_REQUEST["numBids"]?>})
</script>

<script type="text/javascript">


	$('#bid-form').live('submit', function(e) {
		alert('Yes');
		e.preventDefault();
		$('#history-table').load("history-table.php", { "itemID" : <?php echo $_REQUEST["itemID"] ?>, "numBids" : <?php echo $_REQUEST["numBids"]?>, "user" : <?php echo "'".$_REQUEST["user"]."'" ?>, "bid" : $('#bid').val(), "selectedTime" : <?php echo "'".$_REQUEST["selectedTime"]."'" ?> });
		return false;
	});
</script>

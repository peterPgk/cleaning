Hi <?=$company->name;?><br />
<br />
You have reached your limit of <?=$plan['max_jobs'];?> per month on the <?=$plan['name'];?>.<br />
<br />
Your limit will reset on the <?=$reset_date;?>
<br />

<?php if(isset($nextplan) && !empty($nextplan)): //TODO refactor?>

<br />
Want more jobs?  
<br />
You can increase your limit by visiting http://compare.ofertiko.com/admin and upgrading your package to <?=$nextplan['name'];?>
<br />

<?php endif; ?>

<br /><br />
See you online,
The  {{ env('SITE_NAME') }} Team

<br /><br />

If you have received this in error please call 020 0000 0000.

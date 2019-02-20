Hello from  {{ env('SITE_NAME') }},<br />
<br />
Congratulations! You've successfully signed up for  {{ env('SITE_NAME') }}! Your partner ID is:
<br />
<?=$company->name;?>
<br />
You are on the <?=$plan->name;?> and are allowed <?=$plan->jobs_limit;?> reservations per
<br />
month.
<br />
Now it's time to activate your account so that customers can start comparing your quotes online:<br />
<b>Your password is: <?=$password;?> </b><br />
<br />
1. Sign in to your account at <a href="http://compare.ofertiko.com/login">http://compare.ofertiko.com/login</a><br />
<br />
2. Follow the on-screen instructions or click the ‘Add Services button’. Setting up services just<br />
<br />
takes a few minutes and involves four simple steps:<br />
<br />
- Selecting the services you offer<br />
<br />
- Entering prices for each service and extras<br />
<br />
- Entering the postcode areas you cover<br />
<br />
- Entering other required information<br />
<br />
Once you complete these steps, your account will be activated. If you need any help setting up<br />
<br />
your account, just call us on 0000 000 0000.<br />
<br />
Ready to get started? Check out our resources for new partners:<br />
<br />
<a href="http://help.compare.ofertiko.com/hc/en-us/categories/201668789"> Partner-Knowledge- Base</a> <br />
<br />
Our step-by- step guides will help you learn about:<br />
<br />
- Adding new services<br />
<br />
- Amending existing services<br />
<br />
- How billing works<br />
<br />
We're delighted to welcome you to  {{ env('SITE_NAME') }}.<br />
<br />
See you online,<br />
<br />
The  {{ env('SITE_NAME') }} Team
